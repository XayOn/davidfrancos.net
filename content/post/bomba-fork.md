+++
author = "David Francos"
title = "Bomba fork, que es, como prevenirla y como pararla"
date = "2019-01-05"
description = "Bomba fork, que es, como prevenirla y como pararla"
summary = "Hace tiempo se hizo popular el concepto de la bomba fork. Sin ser nada nuevo, se popularizaron principalmente por la belleza y sencillez de un par de ejemplos de bomba fork que aparecieron"

tags = [
    "cybersecurity",
    "bomba fork"
]
+++

Hace tiempo se hizo popular el concepto de la bomba fork. Sin ser nada nuevo, se popularizaron principalmente por la belleza y sencillez de un par de ejemplos de bomba fork que aparecieron

Bash:

{{<highlight bash>}}
:(){ :|:& };:
{{</highlight>}}

Windows:

{{<highlight bash>}}
%0|%0
{{</highlight>}}

## ¿Que hace una bomba fork?

**Simplificando**, una bomba fork es un proceso con capacidad de
_autoreplicarse_ que lo hace hasta que deja al sistema sin recursos.

{{< figure class="hpimage" src="/images/harry_potter.gif">}}

Se lanza a si mismo, y las “copias” de si mismo que ha lanzado lanzan a su vez
copias de si mismos. Como podemos imaginar, los recursos de nuestro equipo
tienen limites, y nos encontraremos "ahogados" en tan solo unos segundos, no
podremos ejecutar nada nuevo.

> Habitualmente las bombas fork son ilustrativas, no son muy utilizadas en el
> mundo real

En raras ocasiones se han podido ver siendo usadas de verdad, fuera de un
entorno de pruebas, como ataque de denegación de servicio. Por ejemplo podría
darse el caso de una vulnerabilidad de ejecución remota de código de algún
servicio web para parar el funcionamiento correcto de este, mediante una bomba
fork.

## ¿Como paramos una bomba fork?

Ante nada, prevención. Es muy poco probable que vayamos a encontrarnos con una
situación en la que nos hayan podido lanzar la bomba fork con un usuario
privilegiado o una situación en la que no tengamos acceso a un usuario
privilegiado de la máquina. En unix, podemos limitar el número de procesos
máximos que puede ejecutar el usuario, de forma que sea inferior al máximo que
puede ejecutar el sistema. De este modo, podremos entrar con otro usuario y
matar la bomba fork.

Este es el caso más habitual, y en GNU/Linux podemos hacerlo con:

{{<highlight bash>}}
ulimit -u 16384
{{</highlight>}}

También es posible añadir el limite a _/etc/security/limits.conf_, haciendolo
persistente. El fichero _limits.conf_ tiene más información al respecto.

En el otro caso, en caso de que la bomba se haya ejecutado como usuario
privilegiado, es necesario disponer de acceso a una terminal, y tener mucha
suerte y habilidad. Si no disponemos de un proceso ya arrancado al que
engancharse y desde el que se pueda ejecutar, por lo menos, listado de
ficheros, apertura de ficheros y envío de señales, es posible que la única
solución sea un reinicio físico.

![The IT Crowd](/images/it_crowd.gif)

Sin embargo disponiendo de acceso a una terminal en bash con un usuario
privilegiado, podemos intentar parar todos los procesos de la bomba fork con
SIGSTOP, eso sí, tenemos que tener en cuenta que no vamos a poder arrancar
nuevos procesos, por lo que no disponemos de los comandos más básicos, como ls,
pkill, killall…

Para solucionar esto, he escrito un par de sencillas funciónes en bash que
implementan listado de procesos y envío de señales a procesos que coincidan con
una expresión regular:

{{<highlight bash>}}
psf(){ for file in /proc/*/cmdline; do read cmdline < $file; echo $cmdline; done } pkill(){ret=1; for file in /proc/*/cmdline; do read cmdline < $file; [[ $cmdline =~ .*$2.* ]] && { IFS='/' read -ra pid <<< "$file"; ret=0; [[ $1 != $$ ]] && kill $1 ${pid[2]};}; done; return $ret; };
{{</highlight>}}

Con el comando _psf_ podemos analizar el listado de procesos, ver que linea de
comandos ha iniciado el proceso de la bomba fork y matar todos. Además,
evitamos matar la sesión de bash en la que estamos en este momento.

Con esto, una vez hemos visto el culpable, simplemente se les lanzan las
señales _SIGSTOP_ y _SIGKILL_.

Las señales en _UNIX_ (y otros sistemas operativos _POSIX_) son una forma de
comunicación entre procesos. Una forma de comunicar eventos a los mismos. Estas
dos señales, _SIGSTOP_ y _SIGKILL_, se encargan respectivamente de congelar un
proceso y de terminarlo a la fuerza. Además el proceso no puede ignorarlas, al
recibirlas está obligado a pararse y morir.

Parar una bomba fork en bash requeriría hacerlo contra todos los procesos de
bash:

{{<highlight bash>}}
pkill -SIGSTOP bash
pkill -SIGKILL bash
{{</highlight>}}

Esto paralizaría la ejecución de todos los procesos actuales de la bomba fork,
sin matarlos, de forma que ninguno de ellos se replicaría. Una vez parados, los
terminaría, dejando nuestro sistema libre ella.

Esta es una forma de escapar de una bomba fork en este escenario tan
particular. No hay que olvidar la sección de prevención de este artículo, y
tener unas políticas de seguridad básicas, si alguien ha lanzado una bomba fork
como root en un sistema, el problema grave está en otra parte.
