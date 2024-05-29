@echo off
cd /d %~dp0
symfony server:start -d
start http://127.0.0.1:8000
