cd `dirname $0`
PWD=`pwd`
ps aux | awk '{print $11,$NF}' | grep -v '^$' | sort | uniq  > ${PWD}/../config/process


