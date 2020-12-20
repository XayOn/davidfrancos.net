+++
author = "David Francos"
title = "Advent of code - Día 3"
date = "2020-12-03"
description = "Advent Of Code - Día 3"
summary = "Continuamos con la serie 'Advent of Code', con el ejercicio del tercer día"

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

Esta vez, vamos al tercer problema, disponible en la [web del advent of
code](https://adventofcode.com/2020/day/3)

> With the toboggan login problems resolved, you set off
> toward the airport. While travel by toboggan might be
> easy, it's certainly not safe: there's very minimal
> steering and the area is covered in trees. You'll need to
> see which angles will take you near the fewest trees.

> Due to the local geology, trees in this area only grow on
> exact integer coordinates in a grid. You make a map (your
> puzzle input) of the open squares (.) and trees (#) you
> can see. For example:

{{<highlight python>}}

..##.......
#...#...#..
.#....#..#.
..#.#...#.#
.#...##..#.
..#.##.....
.#.#.#....#
.#........#
#.##...#...
#...##....#
.#..#...#.#
{{< /highlight >}}

Bueno, el primer problema que nos encontramos de tipo grid. 

> These aren't the only trees, though; due to something you read about once
> involving arboreal genetics and biome stability, the same pattern repeats to
> the right many times:


{{<highlight python>}}

..##.........##.........##.........##.........##.........##.......  --->
#...#...#..#...#...#..#...#...#..#...#...#..#...#...#..#...#...#..
.#....#..#..#....#..#..#....#..#..#....#..#..#....#..#..#....#..#.
..#.#...#.#..#.#...#.#..#.#...#.#..#.#...#.#..#.#...#.#..#.#...#.#
.#...##..#..#...##..#..#...##..#..#...##..#..#...##..#..#...##..#.
..#.##.......#.##.......#.##.......#.##.......#.##.......#.##.....  --->
.#.#.#....#.#.#.#....#.#.#.#....#.#.#.#....#.#.#.#....#.#.#.#....#
.#........#.#........#.#........#.#........#.#........#.#........#
#.##...#...#.##...#...#.##...#...#.##...#...#.##...#...#.##...#...
#...##....##...##....##...##....##...##....##...##....##...##....#
.#..#...#.#.#..#...#.#.#..#...#.#.#..#...#.#.#..#...#.#.#..#...#.#  --->
{{< /highlight >}}

> You start on the open square (.) in the top-left corner and need to reach the
> bottom (below the bottom-most row on your map).

> The toboggan can only follow a few specific slopes (you opted for a cheaper
> model that prefers rational numbers); start by counting all the trees you
> would encounter for the slope right 3, down 1:

> From your starting position at the top-left, check the position that is right
> 3 and down 1. Then, check the position that is right 3 and down 1 from there,
> and so on until you go past the bottom of the map.

> The locations you'd check in the above example are marked here with O where
> there was an open square and X where there was a tree:

{{<highlight python>}}
..##.........##.........##.........##.........##.........##.......  --->
#..O#...#..#...#...#..#...#...#..#...#...#..#...#...#..#...#...#..
.#....X..#..#....#..#..#....#..#..#....#..#..#....#..#..#....#..#.
..#.#...#O#..#.#...#.#..#.#...#.#..#.#...#.#..#.#...#.#..#.#...#.#
.#...##..#..X...##..#..#...##..#..#...##..#..#...##..#..#...##..#.
..#.##.......#.X#.......#.##.......#.##.......#.##.......#.##.....  --->
.#.#.#....#.#.#.#.O..#.#.#.#....#.#.#.#....#.#.#.#....#.#.#.#....#
.#........#.#........X.#........#.#........#.#........#.#........#
#.##...#...#.##...#...#.X#...#...#.##...#...#.##...#...#.##...#...
#...##....##...##....##...#X....##...##....##...##....##...##....#
.#..#...#.#.#..#...#.#.#..#...X.#.#..#...#.#.#..#...#.#.#..#...#.#  --->
{{< /highlight >}}

> In this example, traversing the map using this slope would cause you to
> encounter 7 trees.

> Starting at the top-left corner of your map and following a slope of right 3
> and down 1, how many trees would you encounter?

Tambien es el que mas largo tiene el enunciado!
Así en resumen, siguiendo un mismo movimiento (3 derecha, 1 abajo), ¿Cuantos
arboles nos encontramos bajando por este mapa?

# Primera parte

Primero, tenemos que compensar el asunto de que el mapa se "repita" hacia la
derecha, y movernos por el grid.

Para ello, primero procesamos la entrada:

{{<highlight python>}}

grid = [[a for a in a if a != '\n'] for a in open(sys.argv[1]).readlines()]

{{< /highlight >}}

Aqui hay una chorrada muy importante. No podemos tener en cuenta los saltos de
linea, readlines() nos devuelve los mismos, y en la primera ejecución que hice
me descuadró por eso.

Ahora, necesitamos poder navegar por el grid, para ello, vamos a montar un
generador que itere por todos las posiciones, haciendo los movimientos que necesitamos.

Para ello vamos a usar el tercer parametro de range, que nos permite decidir el
step, iterar por cada elemento de ese step. 
Con esto, vamos iterar por el grid por la posicion x, que es la fija, la que no
se repite. Nos falta la posicion que se repite. La y, esta la sumaremos
manualmente.

Ahora, solo nos falta tener en cuenta la repeticion del grid. Para eso, vamos a
operar sobre el grid inicial, y ajustar la posicion de y, para ajustar la
posicion de y, dividiremos la posicion entre el total de anchura, quedandonos
con el resto de la division, para eso utilizaremos el [operador
modulo](https://docs.python.org/3/reference/expressions.html).

> The % (modulo) operator yields the remainder from the division of the first
> argument by the second. The numeric arguments are first converted to a common
> type. A zero right argument raises the ZeroDivisionError exception. The
> arguments may be floating point numbers, e.g., 3.14%0.7 equals 0.34 (since
> 3.14 equals 4\*0.7 + 0.34.) The modulo operator always yields a result with
> the same sign as its second operand (or zero); the absolute value of the
> result is strictly smaller than the absolute value of the second operand 1.

Aqui he asumido que el total de anchura *no* es constante entre distintos
puzzles, ni en el mismo, sin embargo es muy posible que lo sea, por lo que el
len(grid[pos_x]) nos sobraría.

{{<highlight python>}}

def traverse_grid(grid, step_x, step_y, curr_pos=0):
    for pos_x in range(step_x, len(grid), step_x):
        curr_pos += step_y
        yield grid[pos_x][curr_pos % len(grid[pos_x])] == '#'

{{< /highlight >}}

Finalmente, solo necesitamos ejecutar la funcion con cada uno de los
movimientos (x, y), y, como dice el ejercicio, multiplicar todos entre ellos.

> Time to check the rest of the slopes - you need to minimize the probability
> of a sudden arboreal stop, after all.
> Determine the number of trees you would encounter if, for each of the
> following slopes, you start at the top-left corner and traverse the map all
> the way to the bottom:
> In the above example, these slopes would find 2, 7, 3, 4, and 2 tree(s)
> respectively; multiplied together, these produce the answer 336.

Vamos, que tenemos que repetir lo mismo, con distintos valores de movimiento.
Como nuestra funcion inicial ya permitia especificar los steps de los
movimientos, simplemente tenemos que llamarla por cada slope. 

{{<highlight python>}}
grid = [[a for a in a if a != '\n'] for a in open(sys.argv[1]).readlines()]
slopes = ((1, 1), (1, 3), (1, 5), (1, 7), (2, 1))
res = [Counter(traverse_grid(grid, *slope))[True] for slope in slopes]
print(dict(zip(slopes, res)))
print(reduce(mul, res))
{{< /highlight >}}

Con esto completamos el tercer ejercicio del Advent Of Code!
