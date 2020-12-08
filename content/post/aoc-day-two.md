+++
author = "David Francos"
title = "Advent of code - Día 2"
date = "2020-12-02"
description = "Advent Of Code - Día 2"
summary = "Continuamos con la serie 'Advent of Code', con el ejercicio del segundo día"

tags = [
    "python",
    "adventofcode",
]
+++

Recordemos el [Advent Of Code](https://adventofcode.com/), un calendario de
adviento para *"los nuestros"*, el advent of code se define a si mismo como:

> Advent of Code is an Advent calendar of small programming puzzles for a
> variety of skill sets and skill levels that can be solved in any programming
> language you like. 

Continuo registrando todas las soluciones en un [repositorio en mi
github](https://github.com/XayOn/aoc2020).

Esta vez, vamos al segundo problema, disponible en la [web del advent of
code](https://adventofcode.com/2020/day/2)

> Their password database seems to be a little corrupted: some of the passwords
> wouldn't have been allowed by the Official Toboggan Corporate Policy that was
> in effect when they were chosen.
> To try to debug the problem, they have created a list (your puzzle input) of
> passwords (according to the corrupted database) and the corporate policy when
> that password was set.
> Each line gives the password policy and then the password. The password
> policy indicates the lowest and highest number of times a given letter must
> appear for the password to be valid. For example, 1-3 a means that the
> password must contain a at least 1 time and at most 3 times.

# Primera parte

Bien, parece sencillo, lo que tenemos que hacer es **procesar el listado de
reglas por cada contraseña, comprobarlo, y contar los resultados**.

Vamos a empezar por *procesar la entrada*. Para eso, vamos a usar una expresión
regular, viendo que la entrada tiene esta pinta:

> 1-3 a: abcde

Que podemos sacar con una empresión regular como la siguiente:

{{<highlight python>}}
LNS = re.compile('(\d+)-(\d+) (\w): (.*)')
{{< /highlight >}}

Las reglas tienen en cuenta el **numero** de letras, para contar las
apariciones de una letra en cada cadena, utilizaremos la [colección
Counter](https://docs.python.org/3/library/collections.html#collections.Counter)

Counter cuenta las ocurrencias de *elementos hasheables*. En este caso, vamos a
contar cuantas veces aparece una letra en un string.

{{<highlight python>}}
>>> Counter("foobar")
Counter({'o': 2, 'f': 1, 'b': 1, 'a': 1, 'r': 1})
{{< /highlight >}}

Como podeis ver, aqui vemos que la letra `o` aparece dos veces. Lo que nos
piden las normas es que comprobemos que este entre dos valores, por lo que
tendremos que hacer algo como lo siguiente:

{{<highlight python>}}
int(min) <= Counter(string)[letter] <= int(max)
{{< /highlight >}}

Vamos a ponerlo todo junto

{{<highlight python>}}
import re, sys
from collections import Counter

LNS = re.compile('(\d+)-(\d+) (\w): (.*)')
POL = lambda m, x, l, s: int(m) <= Counter(s)[l] <= int(x),
res = open(sys.argv[1]).readlines()
print(len([l for l in res if POL(*LNS.match(l).groups())]))
{{< /highlight >}}

Bueno, esta vez nos ha costado unas cuantas lineas más, hemos introducido el
concepto de [groups](https://docs.python.org/3/library/re.html#re.Match.groups)
de una regex, que simplemente devuelve una tupla con todos los grupos definidos
(los cuatro), y hemos utilizado una [función
lambda](https://docs.python.org/3/tutorial/controlflow.html?highlight=lambda#lambda-expressions)

# Segunda parte

¡Y llegamos a la segunda parte!

> Each policy actually describes two positions in the password, where 1 means
> the first character, 2 means the second character, and so on. (Be careful;
> Toboggan Corporate Policies have no concept of "index zero"!) Exactly one of
> these positions must contain the given letter. Other occurrences of the
> letter are irrelevant for the purposes of policy enforcement.

Bueno! Cambiamos del total de letras a posiciones especificas.
Vamos a tener que cambiar nuestra función de comprobación para que compruebe
que haya *una* sola ocurrencia de estas letras. Al ser solo una, no nos sirven
ni `any` ni `all`, asi que recurriremos a un Counter() de nuevo.

{{<highlight python>}}
import re, sys
from collections import Counter

LNS = re.compile('(\d+)-(\d+) (\w): (.*)')
IN = lambda x: int(x) - 1
POL = lambda m, x, l, s: Counter(a == l for a in (s[IN(m)], s[IN(x)]))[True] == 1
res = open(sys.argv[1]).readlines()
print(len([l for l in res if POL(*LNS.match(l).groups())]))
{{< /highlight >}}

Como veis, aqui no aplicamos nada nuevo, simplemente vamos a contar todas las
veces que cualquiera de las dos posiciones es la letra que esperamos, y
comprobar que solo se da una ocurrencia entre las dos opciones. Simplemente hay
que tener en cuenta que hay que restarle 1 a la posicion indicada, recordemos
que **no tienen concepto de indice 0**.

Finalmente, lo ponemos todo junto para dar respuesta a los dos con la menor
cantidad de lineas posibles, para eso utilizamos una lista de lambdas.

{{<highlight python>}}
import re, sys
from collections import Counter

LNS = re.compile('(\d+)-(\d+) (\w): (.*)')
IN = lambda x: int(x) - 1
POLS = [
    lambda m, x, l, s: int(m) <= Counter(s)[l] <= int(x),
    lambda m, x, l, s: Counter(a == l for a in (s[IN(m)], s[IN(x)]))[True] == 1
]
res = open(sys.argv[1]).readlines()
print(len([l for l in res if POLS[int(sys.argv[2])](*LNS.match(l).groups())]))
{{< /highlight >}}

¡**Con esto, en once lineas, damos solucion a los dos problemas**!
Simplemente, indicaremos la version de la politica de contraseñas con el
segundo argumento al script (¡Cuenta desde cero, a diferencia de la propia
politica!)
