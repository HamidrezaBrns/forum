# How to safely share a header file between C and C++ code?

I’m working on a project that mixes **C** and **C++** code. Some modules are written in plain C for portability, but I also have C++ code that needs to call into them.

Right now, I have a header file like this:

```c
// mylib.h
#ifndef MYLIB_H
#define MYLIB_H

int add_numbers(int a, int b);

#endif
```

And the implementation in C:

```c
// mylib.c
#include "mylib.h"

int add_numbers(int a, int b) {
    return a + b;
}
```

When I try to include this header in my C++ code:

```cpp
#include "mylib.h"

int main() {
    int result = add_numbers(2, 3);
    return 0;
}
```

I get **linker errors** because of C++ name mangling. I know I can solve this using `extern "C"`, but I’m not sure of the best way to structure the header so it works with both C and C++ without duplicating code.

Some approaches I’ve seen are:

```c
#ifdef __cplusplus
extern "C" {
#endif

int add_numbers(int a, int b);

#ifdef __cplusplus
}
#endif
```

My questions are:

* Is this the standard and portable way to write headers that can be shared between C and C++?
* Are there any edge cases or pitfalls with this pattern (e.g., when mixing with system headers or third-party libraries)?
* Should every function in a mixed project be wrapped with `extern "C"`, or only the ones intended for cross-language use?

Looking for advice on **best practices** when maintaining libraries that need to work cleanly in both C and C++.

---

Would this be the idiomatic solution, or are there modern alternatives that C/C++ developers are using today?
