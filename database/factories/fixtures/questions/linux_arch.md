# How to properly manage AUR packages on Arch Linux?

I’m running **Arch Linux** and I often need software that isn’t in the official repositories, so I install from the **AUR (Arch User Repository)**. Right now, I’m using `yay` as my AUR helper, but I’ve seen debates about whether it’s better to stick with manual builds using `makepkg`.

For example, installing a package manually looks like this:

```bash
git clone https://aur.archlinux.org/google-chrome.git
cd google-chrome
makepkg -si
```

But with an AUR helper:

```bash
yay -S google-chrome
```

This raises a few questions:

* Is it considered safe to rely on AUR helpers like `yay` or `paru`, or should I manually review and build every package with `makepkg`?
* What’s the recommended way to **update AUR packages** without risking partial upgrades or breaking dependencies?
* Are there best practices for auditing PKGBUILDs before installation, especially for less popular packages?
* How do Arch users typically handle **orphaned AUR packages** that may stop receiving updates?

I’ve looked at the [Arch Wiki on the AUR](https://wiki.archlinux.org/title/Arch_User_Repository), but I’d like to hear from more experienced users about how they balance **security, convenience, and maintainability** when working with AUR packages.

**What’s the best practice workflow for safely managing AUR packages on Arch Linux?**
