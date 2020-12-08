+++
author = "David Francos"
title = "asynccontextmanager – Novedades en python3.7"
date = "2019-02-03"
description = "asynccontextmanager – Novedades en python3.7"
summary = "El concepto de _@asynccontextmanager_ es el mismo que @contextmanager, una forma rápida de definir objetos que son manejadores de contexto, sin necesidad de implementar una clase con los métodos mágicos ___enter___ y ___exit___. Para ello se aprovecha de los generadores asíncronos, una funcionalidad introducida en _python3.7a0_, por lo que ya es familiar para muchos dado que _python3.6_ lleva tiempo siendo la versión estable."

tags = [
"python",
"whatsnew"
]
+++

La semana pasada vio la luz
[python3.7](https://www.python.org/downloads/release/python-370/), todavía está
en version alpha0, pero ya podemos encontrar una lista de cambios interesantes.
El cambio que probablemente vaya a estar más presente en desarrollos futuros es
el decorador _“@contextlib.asynccontextmanager”_, este articulo actua a modo de
pequeña introducción al mismo.

El concepto de _@asynccontextmanager_ es el mismo que @contextmanager, una forma
rápida de definir objetos que son manejadores de contexto, sin necesidad de
implementar una clase con los métodos mágicos ___enter___ y ___exit___. Para
ello se aprovecha de los generadores asíncronos, una funcionalidad introducida
en _python3.7a0_, por lo que ya es familiar para muchos dado que _python3.6_
lleva tiempo siendo la versión estable.

La documentación del modelo de datos de Python establece un manejador de
contexto como un objeto que define el contexto de ejecución que será
establecido cuando ejecutemos una sentencia _“with”_. El manejador de contexto
se encarga de **gestionar la entrada y la salida del contexto de ejecución del
bloque de código**.

Esto es, dado un bloque de código, el manejador de contexto ejecuta código
antes y después del mismo. A modo de "preparación-ejecución-limpieza".

![Async context manager example](/images/asynccontextmanager.png)

En el ejemplo podemos ver que la variable “a” tenia el valor “foo” al inicio
del bloque de código, y al final del mismo tenía el valor “bar”. A su vez
podemos ver que han pasado 10s, que hemos esperado con asyncio.sleep.

Dado que esta funcionalidad sólo esta disponible en python3.7, y este no está
empaquetado todavía, es necesario compilarlo desde el fuente si queremos
probarla.

Es posible hacer el build-dep con python3 en una versión inferior a 3.5, sin
embargo necesitaremos instalar a mano la librería openssl-dev. En debian
podemos hacerlo rápidamente como se puede ver a continuación:

{{<highlight bash>}}
sudo apt-get build-dep python3.5
git clone https://github.com/python/cpython
cd cpython
sh configure && make
sudo make install
{{< /highlight >}}
