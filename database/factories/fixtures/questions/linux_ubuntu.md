# How to properly set environment variables system-wide on Ubuntu?

I’m running **Ubuntu 22.04** and I need to set some environment variables (like `JAVA_HOME`, `PATH`, and a few custom ones) so that they are available for **all users and all shells**.

I’ve seen several different approaches online, but I’m not sure which one is considered the **correct or modern way**:

1. Adding them to `~/.bashrc` or `~/.zshrc` → works only for one user and only for interactive shells.
2. Adding them to `~/.profile` → seems to load for login shells, but not always for non-interactive shells (like scripts run by cron).
3. Using `/etc/environment` → seems to be global, but I’ve read it doesn’t support expansions like `$PATH:/new/path`.
4. Creating a custom script under `/etc/profile.d/` → looks like a clean solution, but I’m unsure if it works for non-login shells.

For example, I want something like:

```bash
export JAVA_HOME=/usr/lib/jvm/java-17-openjdk-amd64
export PATH=$PATH:$JAVA_HOME/bin:/opt/myapp/bin
```

My questions are:

* What’s the recommended way to set environment variables system-wide on Ubuntu?
* How do I make sure they are available for **all users**, and for **non-interactive processes** (like systemd services or cron jobs)?
* Is `/etc/environment` still the preferred method, or should I rely on `/etc/profile.d/*.sh`?

I found [this AskUbuntu thread](https://askubuntu.com/questions/58814/how-do-i-add-environment-variables) but answers seem inconsistent depending on the Ubuntu version.

What’s the best practice in modern Ubuntu setups?
