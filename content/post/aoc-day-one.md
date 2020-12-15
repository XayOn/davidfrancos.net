+++
author = "David Francos"
title = "Advent of code - Día 1"
date = "2020-12-01"
description = "Advent Of Code - Día 1"
summary = "El desarrollo de software es como cualquier otra disciplina: *require practica*.  De vez en cuando, podemos practicar con eventos que doblan como juegos para los que nos gusta. Aqui es donde entra el Advent Of Code, un calendario de adviento para *\"los nuestros\"*. En este post, resolveremos el primer desafio"

tags = [
    "python",
    "adventofcode",
]
+++

El desarrollo de software es como cualquier otra disciplina: *require practica*.
De vez en cuando, podemos practicar con eventos que doblan como juegos para
los que nos gusta. No soy especialmente fan de los juegos, salvo algun juego de
mesa de vez en cuando, o algun juego para movil (todo tiene su lugar :toilet:),
sin embargo, este tipo de **puzzles mentales** sirven como un buen entretenimiento.

Este més, en mi ciudad natal (Zaragoza) vamos a seguir semi-confinados, si bien
parece que la [tendencia de casos esta a la
baja](https://www.heraldo.es/noticias/aragon/2020/12/08/los-casos-de-coronavirus-siguen-su-tendencia-a-la-baja-con-nuevos-positivos-1409148.html),
con solo 125 a dia de hoy, por lo que cualquier nuevo entretenimiento va a ser
bienvenido :wink:

Aqui es donde entra el [Advent Of Code](https://adventofcode.com/), un calendario de adviento para *"los
nuestros"*, el advent of code se define a si mismo como:

> Advent of Code is an Advent calendar of small programming puzzles for a
> variety of skill sets and skill levels that can be solved in any programming
> language you like. 

Voy a registrar todas las soluciones en un [repositorio en mi
github](https://github.com/XayOn/aoc2020), con poetry para manejar entornos
virtuales (aunque utilizare las minimas librerias externas que pueda). 

Vamos al primer problema, disponible en la [web del advent of
code](https://adventofcode.com/2020/day/1) una vez nos registremos, veremos la
"historia de introducción", viendo que hay una narrativa detrás del evento,
cada dia va a tener una historieta distinta.

> To save your vacation, you need to get all fifty stars by December 25th.

¡Esta será la duración del evento! Cada problema nos dará dos estrellas, 25
dias de duración, **50 estrellas en total**. Se pueden resolver con "retraso", por
lo que no hay problema si empezais hoy. Incluso podeis resolver las de años
anteriores.

# Primera parte

Vamos al puzzle en cuestión:

> Before you leave, the Elves in accounting just need you to fix your expense
> report (your puzzle input); apparently, something isn't quite adding up.
> Specifically, they need you to find the two entries that sum to 2020 and then
> multiply those two numbers together.

Para resolver este problema, vamos a operar sobre todas las combinaciones
posibles de los numeros de entrada. Python tiene una funcion `combinations` en
el modulo itertools que nos viene de perlas.

Primero, vamos a limpiar nuestros datos de entrada:
{{<highlight python>}}
nms = [int(a) for a in open(sys.argv[1]).readlines()]
{{</highlight>}}

Con esto, por cada linea del reporte (`puzzle input`) tenemos un entero en una
lista. Tenemos que encontrar los DOS numeros que sumen `2020`, por lo que vamos
a utilizar una funcion de `itertools`, la función `combinations`, que la
documentación de python define como:

> Return r length subsequences of elements from the input iterable

Por lo que nos va a devolver, todas las combinaciones posible de elementos en
nuestra entrada. 

{{<highlight python>}}
from itertools import combinations
nms = combinations([int(a) for a in open(sys.argv[1]).readlines()], 2)
{{< /highlight >}}

Con esto ya disponemos de la entrada, ahora solo tenemos que **multiplicar los
dos numeros que den 2020**. Como solo va a haber un resultado positivo (y en todo
caso solo necesitariamos el primero), vamos a hacer una `generator expression`
y vamos a pedirle el primer resultado que coincida con `next`

{{<highlight python>}}
from operator import add, mull
print(next(mul(*a) for a in nms if sum(*a) == 2020))
{{< /highlight >}}

Aquí utilizamos varios conceptos:

- [next](https://docs.python.org/3/library/functions.html#next)
- [operator.mul y operator.sum](https://docs.python.org/3/library/operator.html?highlight=operator)
- [argument unpacking](https://docs.python.org/3/tutorial/controlflow.html#unpacking-argument-lists)

*Operator.mul y operator.sum* son, simplemente `*` y `+` **expuestos como
funciones. Los utilizamos así para poder usar argument unpacking, que queda mas limpio**.
Next, por su parte, devuelve el siguiente elemento de un iterador. En este caso
el iterador es la generator expression, por lo que nos devolverá su primer
resultado, evitando precalcular todos los posibles.

Con esto, tenemos la respuesta al primer problema, ya tenemos nuestra primera
estrella, ahora ... ¡A por la segunda!

# Primera parte

> The Elves in accounting are thankful for your help; one of them even offers
> you a starfish coin they had left over from a past vacation. They offer you a
> second one if you can find three numbers in your expense report that meet the
> same criteria.

Vamos, necesitamos resolver el mismo problema, pero en lugar de una
combinatoria de dos numeros, la tenemos de tres. 

La primera parte no es un problema, ya que ya estamos usando combinations, y
combinations soporta directamente un parametro `r` que nos permite indicar la
longitud de las tuplas de las combinaciones que genera, por lo que solo
tendremos que cambiar este parametro. Para que la misma solución nos sirva en
ambas partes, vamos a permitir que este paremtro venga dado por los parametros
de entrada del comando, usando
[sys.argv](https://docs.python.org/3/library/sys.html?highlight=operator#sys.argv)

Para la segunda parte, lo que necesitamos es multiplicar y sumar el primer
numero, por el segundo, por el tercero...
Esto se resuelve muy facilmente con `reduce`
[reduce](https://en.wikipedia.org/wiki/Fold_%28higher-order_function%29), que
en python está disponible en el modulo functools. Como ya hemos usado operator,
no tenemos que cambiar practicamente nada.

Así que, con estos cambios, nuestro código quedaría como el que sigue:

{{<highlight python>}}
from functools import reduce
from itertools import combinations
from operator import add, mul
from sys import argv

nms = combinations([int(a) for a in open(argv[1]).readlines()], int(argv[2]))
print(next(reduce(mul, a) for a in nms if reduce(add, a) == 2020))
{{< /highlight >}}

**¡Y con esto, terminamos el reto de hoy, y nos vamos con nuestras dos estrellas!**

