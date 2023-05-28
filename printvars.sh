#!/bin/bash

filename=""
if [ -f GNUmakefile ] ; then
  filename="GNUmakefile"
elif [ -f makefile ] ; then
  filename="makefile"
elif [ -f Makefile ] ; then
  filename="Makefile"
fi
if [ -n "$filename" ] ; then
  vars=""
  for n in $@ ; do
    vars="$vars print-$n"
  done
  gmake -f $filename -f printvar.mak $vars
else
  echo "No makefile found" 1>&2
  exit 1
fi