#!/bin/bash
git fetch --all
git reset --hard origin/master
cd EduMS
git fetch --all
git reset --hard origin/master
cd ..
cd EduMS-client/
git fetch --all
git reset --hard origin/master
cd ..
cd SQMS/
git fetch --all
git reset --hard origin/master
cd ..
cd mITSM-partner-network/
git fetch --all
git reset --hard origin/master
cd ..

