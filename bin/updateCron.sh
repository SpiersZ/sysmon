cd `dirname $0`
PWD=`pwd`
crontab -l | grep -v '^$' | sort | uniq  > ${PWD}/../config/crontab


