## Synopsis

Heart of the system. This is where everything not belonging to a devops, customer, employee or staff member -- that that is part of the system as a whole to bring it together -- goes.

This is where the scripts that manage the other scripts and system files will go.

Also system services like output functions, data normalization, bindings, sessions, system state management, cron / schedule tasks, etc. will be controlled and managed from here.

## Code Example

Will fill out later.


## Motivation

Observing a need / problem in the industry whereby staffing services need to become mobile, a demand where applicants are favoring and using only mobile, and business owners and managers requiring their vendors have mobile / web management and instant reporting available.

However the problem personnelink has and must solve is that of no people or staff to stand behind the curtains and pull strings this round, and that for the system to be fully able to help applicants, employees and employers connect and work together, the system would need to be completely humanless.

A second problem is introduced as well as the project has limited development resources available; therefore requires things to be as simple and efficient as possible to put together, run and maintain. 

Freeminding on the notion and concluded the only way to solve all problems would be to deploy my legacy scripts, that for most part already embody all the business procedures and logic -- in some shape or form and somewhere -- and therefore the problem that remains is there is no people to operate it, and I don't feel like stringing together a bunch of scripts in a chain of long dominoes connected together again like I did in legacy.


Therefore, the solution, is robots.

For the things that are already done, I won't redo, I'll fold them into the new system. And for the things that need people, I'll make a system call to a core class that will build a robot and then "program" that robot with whatever it's role will be at the moment -- be it a receptionist, or placement person, manager, supervisor, payroll supervisor or even an accountant... 

From there I'll program a heartbeat for the system, say initially set it to tick at every 5 seconds and see how that works. So 12 times every minute the systems heartbeat will pulse, that pulse will spin up one robot and that robot will run through it's checklist of items it monitors; these items will exist in the form of a series of flags that will be set by other worker robots. Depending on what flags are set will determine whether or not additional robots get built and dispatched to do work.

So essentially the system will be comprised as a series of robots all running around doing their predetermined things. This architecture should allow the bulk of services to be unmarried from each other, scripts to run more stateless as they wont have to care what other scripts are doing. The worker robots will simply have to set flags saying that a certain type of work was done, and this in tern on the next system heartbeat will drive the main worker robot to build and dispatch the corresponding required worker bot to "do things" ...

And I think that's it. That should allow for a vms architecture devoid of people, other than the ones geared to help and or where required.

So, an example scenario goes like this ...

- Applicants apply online (applicant pool builds) 
- Clients fill out job orders
- Employee fills out time

- System heartbeat ticks, worker bot is instantiated and comes to life, runs query against database for checklist of items with "flags set" [and actually won't be a db query, will be a method nested inside bot that calls itself, and returns parts of itself] and ...

- if empty record set aka no flags, destroys itself and system returns to initial state until next heartbeat

- if records returned, worker bot knows something happened and will spin up additional worker bots [based on itself] to spin off and deal with their individual list of exceptions and eventualities (for example email notifications that time has been submitted, alerts for responses to job postings, signaling posting of jobs based on job status modification by other worker bots / users, moving submitted or approved time from one table to another -- freeing up worker bots to do more, less complex tasks, and that fire independent of each other -- simplifying design, elimenating race conditions and reducing issues resultant from human error or inconsistencies)

## Installation

Provide code examples and explanations of how to get the project.

## API Reference

Will complete later.

## Tests

Will describe and show how to run the tests with code examples later.
