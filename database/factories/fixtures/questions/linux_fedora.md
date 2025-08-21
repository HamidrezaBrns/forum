# How to manage multiple kernel versions safely on Fedora Linux?

I’m using **Fedora 40** on my development machine, and I’ve noticed that after a few system updates, I now have several kernel versions installed. For example, running:

```bash
$ rpm -q kernel
kernel-6.9.5-200.fc40.x86_64
kernel-6.8.11-200.fc40.x86_64
kernel-6.7.12-200.fc40.x86_64
```

I understand that Fedora keeps a few older kernels as a fallback, but I’m a bit unclear on the best practices for managing them:

* How many old kernels does Fedora keep by default, and where is this configured?
* Is it safe to manually remove old kernels with `dnf remove kernel-*`, or is there a cleaner way?
* If I want to **keep only the latest and one fallback kernel**, what’s the recommended approach?
* How can I set a specific kernel as the default at boot time (for example, if a new kernel breaks something and I need to revert)?

I’ve seen references to `dnf autoremove` and `dnf remove --oldinstallonly`, and also some mention of the `installonly_limit` option in `dnf.conf`, but I’m not sure which is the “right” way in Fedora today.

What’s the proper Fedora/Linux best practice for **controlling kernel versions**, keeping the system safe but not letting old kernels pile up unnecessarily?

Would love to hear how other Fedora users handle this.
