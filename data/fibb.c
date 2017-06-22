#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#define STRINGIFY(x) #x
#define MACRO(x)     STRINGIFY(x)

char *readIn() {
    char *line = NULL;
    size_t size;
    if (getline(&line, &size, stdin) != -1) {
        return line;
    }
    return "";
}

int ReadInt() {
    return atoi(readIn());
}

uintmax_t fibonacci(uintmax_t k) {
    if (k < 2) {
        return k;
    }
    uintmax_t f = fibonacci(k-1) + fibonacci(k-2);
    #ifdef DEBUG
    //printf("D:fibonacci(%lu): %lu\n", f, f);
    #endif
    return f;
}

int main(int argc, char const *argv[]) {
    #ifdef DEBUG
    printf("+--------------------+\n| Build ID: %s |\n+--------------------+\n\n", MACRO(BUILDID));
    #endif

    int inp = ReadInt();

    printf("\nfibonacci(%i): %lu\n", inp, fibonacci(inp));

    return 0;
}
