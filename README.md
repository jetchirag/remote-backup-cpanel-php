# remote-backup-cpanel-php
Remotely backup cPanel accounts (even for v74+)

# Install
1. Clone the repo
2. Goto repo folder and run composer install command
3. Configure main settings in conf.php
4. Configure individual settings in conf_example.php

# Backup multiple servers
1. Copy conf_example.php into another file conf_[name].php (where [name] can be anything)
2. Update file with login details of new server
3. Goto run.php and find conf_example.php
4. Below it write
`require_once "conf_[name].php"; `
5. Save file 

# How to run backups periodically?
Add following to cron

```
0 0 * * 0 /usr/bin/php /path/to/script/run.php
```

Update timing and php location accordingly

# Todos
1. Add error catching
2. Logging output to file


