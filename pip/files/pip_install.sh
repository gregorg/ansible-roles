#!/bin/bash


#curl https://bootstrap.pypa.io/get-pip.py -o - | python
curl https://bootstrap.pypa.io/get-pip.py -o - | python3 || curl https://bootstrap.pypa.io/pip/3.5/get-pip.py -o - | python3
