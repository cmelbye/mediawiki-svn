#ifndef TTREE_H
#define TTREE_H

typedef struct tnode { 
  unsigned char splitchar; 
  struct tnode *lokid, *eqkid, *hikid; 
  void *data;
} Tnode;

Tnode *insert(Tnode *t, const unsigned char *s, void *data);
Tnode *insertWithFree(Tnode *t, const unsigned char *s, void *data);
void *search(Tnode *root, const unsigned char *s);
void *searchMax(Tnode *root, const unsigned char *s, int *len);
int searchAll(Tnode *root, const unsigned char *s, int *lenarray, void *dataarray[]);
void freeTree(Tnode *t);
#endif
