#
#  Build system entrypoint for Personnelink, the Personnelink commerical staffing
#  application.
#
#  See BUILD.md for details.
# 

default: all


######################
#   Env Overrides    #
######################
VERBOSE ?= 1


TOOLS := tools/bin
PROJECT := $(shell $(TOOLS)/projectname)
BRANCH := $(shell git rev-parse --abbrev-ref HEAD)
SHELL := /bin/bash

docker_image = $(PROJECT)-$(1):$(BRANCH)

APP_BASE_IMAGE := $(call docker_image,app-base)
APP_WEB_IMAGE := $(call docker_image,app-web)
APP_TEST_IMAGE := $(call docker_image,app-test)
DB_IMAGE := $(call docker_image,db)
SCHED_IMAGE := $(call docker_image,sched)
CUMANAGE_IMAGE := $(call docker_image,cumanage-tool)

CLEAN_IMAGES := \
	$(APP_BASE_IMAGE) \
	$(APP_WEB_IMAGE) \
	$(APP_TEST_IMAGE) \
	$(DB_IMAGE) \
	$(SCHED_IMAGE) \
	$(CUMANAGE_IMAGE)

DOCKERFILES_SRC := \
	app/Dockerfile.base \
	app/Dockerfile.web \
	app/Dockerfile.test \
	sched/Dockerfile

IGNORE_DEP_PATTERNS := \
	%.swp

APP_BASE := .app-base.build
APP_WEB := .app-web.build
APP_TEST := .app-test.build
DB := .db.build
SCHED := .sched.build
CUMANAGE := .cumanage.build

finddeps = $(filter-out $(IGNORE_DEP_PATTERNS),$(shell find $(1) -type f))

DOCKERFILES := $(subst Dockerfile,.Dockerfile,$(DOCKERFILES_SRC))

APP_BASE_DEP := .app-base.dep
APP_BASE_DEPS := \
	Makefile \
	$(call finddeps, app/conf/base app/bootstrap/base)
$(APP_BASE_DEP): $(APP_BASE_DEPS)

APP_WEB_DEP := .app-web.dep
APP_WEB_DEPS := \
	$(TOOLS)/buildmeta \
	$(call finddeps, app/web app/conf/web app/bootstrap/web)
$(APP_WEB_DEP): $(APP_WEB_DEPS)

APP_TEST_DEP := .app-test.dep
APP_TEST_DEPS := \
	$(call finddeps, app/conf/test app/bootstrap/test app/test)
$(APP_TEST_DEP): $(APP_TEST_DEPS)

DB_DEP := .db.dep
DB_DEPS := \
	db/Dockerfile \
	$(call finddeps, db/initdb.d)
$(DB_DEP): $(DB_DEPS)

SCHED_DEP := .sched.dep
SCHED_DEPS := \
	$(call finddeps, sched/tasks sched/crontab sched/conf)
$(SCHED_DEP): $(SCHED_DEPS)

CUMANAGE_DEP := .cumanage.dep
CUMANAGE_DEPS := \
	$(call finddeps, tools/cumanage)
$(CUMANAGE_DEP): $(CUMANAGE_DEPS)


ifeq ($(VERBOSE),1)
  VFILTER :=
else
  VFILTER := >/dev/null
endif

######################
#   Public Targets   #
######################

help:
	@less BUILD.md

all stack: web db sched cumanage

stack-up run: stack
	$(TOOLS)/stack up

dev-run: stack
	DEVMODE=1 $(TOOLS)/stack up

stack-down stop:
	$(TOOLS)/stack down

stack-open:
	$(TOOLS)/stack open

web: $(APP_WEB)

test: $(APP_TEST)
	$(TOOLS)/test --colors auto

test-prompt: $(APP_TEST)
	$(TOOLS)/test --shell

db: $(DB)

sched: $(SCHED)

cumanage: $(CUMANAGE)

web-shell:
	$(TOOLS)/webshell

sched-shell: sched
	$(TOOLS)/schedshell

db-shell:
	$(TOOLS)/dbshell

db-admin:
	$(TOOLS)/dbadmin

db-prompt:
	$(TOOLS)/dbprompt

clean:
	-$(TOOLS)/stack down 2>/dev/null
	-docker rmi -f $(CLEAN_IMAGES) 2>/dev/null
	rm -f \
		$(APP_BASE) \
		$(APP_WEB) \
		$(APP_TEST) \
		$(DB) \
		$(SCHED) \
		$(CUMANAGE) \
		$(ALL_DEP) \
		$(DOCKERFILES)
	$(MAKE) home-clean

db-clean:
	$(TOOLS)/stack stop db 2>/dev/null
	$(TOOLS)/stack rm --all -f db 2>/dev/null
	-docker rmi -f $(DB_IMAGE) 2>/dev/null
	rm -f $(DB)

sched-clean:
	-docker rmi -f $(SCHED_IMAGE) 2>/dev/null
	rm -f $(SCHED)

cumanage-clean:
	-docker rmi -f $(CUMANAGE_IMAGE) 2>/dev/null
	rm -f $(CUMANAGE)

deploy: deploy-stage

deploy-stage: stack
	$(TOOLS)/deploy --stack stage

deploy-prod: stack
	$(TOOLS)/deploy --stack prod
hello:
	@echo "hello"
	
print-%:
        @echo '$*=$($*)'

HOMEIGNORE := \
	README.md \
	httpd

home-clean:
	rm -rf $(filter-out $(addprefix home/,$(HOMEIGNORE)),$(wildcard home/*))


#########################
#   Internal Use Only   #
#########################

ALL_DEP := \
	$(APP_BASE_DEP) \
	$(APP_WEB_DEP) \
	$(APP_TEST_DEP) \
	$(DB_DEP) \
	$(SCHED_DEP) \
	$(CUMANAGE_DEP)

ALL_DEPS := $(sort \
	$(APP_BASE_DEPS) \
	$(APP_WEB_DEPS) \
	$(APP_TEST_DEPS) \
	$(DB_DEPS) \
	$(SCHED_DEPS)) \
	$(CUMANAGE_DEPS)

$(DOCKERFILES): $(DOCKERFILES_SRC)
	@echo Generating Dockerfile: $@
	@sed \
		-e 's/__PROJECT__/$(PROJECT)/' \
		-e 's/__BRANCH__/$(BRANCH)/' \
		$(subst .Dockerfile,Dockerfile,$@) > $@


key_value_title = $(shell echo -e "\n\033[1m$(1): \033[94m$(2)\033[0m")
build_title = $(call key_value_title,Building,$(1))

$(APP_BASE): app/.Dockerfile.base $(APP_BASE_DEP)
	@echo $(call build_title,Application Base Container)
	docker build -f app/.Dockerfile.base -t $(APP_BASE_IMAGE) app $(VFILTER)
	@touch $@

$(APP_WEB): app/.Dockerfile.web $(APP_BASE) $(APP_WEB_DEP)
	@echo $(call build_title,Application Web Container)
	@$(TOOLS)/buildmeta > app/.build
	docker build -f app/.Dockerfile.web -t $(APP_WEB_IMAGE) app $(VFILTER)
	@touch $@

$(APP_TEST): app/.Dockerfile.test $(APP_BASE) $(APP_WEB_DEP) $(APP_TEST_DEP)
	@echo $(call build_title,Test Container)
	docker build -f app/.Dockerfile.test -t $(APP_TEST_IMAGE) app $(VFILTER)
	@touch $@

$(DB): $(DB_DEP)
	@echo $(call build_title,DB Container)
	docker build -t $(DB_IMAGE) db $(VFILTER)
	@touch $@

$(SCHED): sched/.Dockerfile $(APP_BASE) $(SCHED_DEP)
	@echo $(call build_title,Scheduled Tasks Container)
	docker build -f sched/.Dockerfile -t $(SCHED_IMAGE) sched $(VFILTER)
	@touch $@

$(CUMANAGE): $(CUMANAGE_DEP)
	@echo $(call build_title,CU Manage Tool Container)
	docker build -t $(CUMANAGE_IMAGE) tools/cumanage $(VFILTER)
	@touch $@


# Prevent implicit targets from invoking.
$(ALL_DEPS): ;

$(ALL_DEP):
	@[ $(words $?) -gt 6 ] && \
	UPDATES="*$(words $?) files*" || \
	UPDATES="$?" && \
	echo -e "Detected update to: \033[95m$$UPDATES\033[0m"
	@touch $@


.PHONY: help clean deploy stack $(APP_TEST)
