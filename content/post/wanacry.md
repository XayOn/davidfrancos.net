+++
author = "David Francos"
title = "El lado bueno del \"ataque masivo\" con WCRY"
date = "2019-05-04"
description = "El lado bueno del \"ataque masivo\" con WCRY"
summary = "Estamos viviendo estos días una de las épocas más duras para muchas empresas que trabajan con equipos con Windows, la incidencia del ransomware WCRY."

tags = [
    "cybersecurity",
    "wanacry"
]
+++

Estamos viviendo estos días una de las épocas más duras para muchas empresas
que trabajan con equipos con Windows, la incidencia del ransomware WCRY.

Como muchos sabréis, el ransomware WCRY, aprovechando una vulnerabilidad en el
protocolo SMB, se ha expandido como la espuma, llegando a infectar más de
200.000 equipos en un largo fin de semana. Largo al menos para muchos
administradores de sistemas, claro.

Los que hemos vivido esto desde fuera hemos podido aprovechar para reflexionar.
En estas situaciones, el primer culpable es, sin lugar a dudas, el que ha
lanzado la campaña del ransomware, pero no podemos evitar recalcar que con una
politica de actualizaciones más agresiva (o no utilizando Microsoft Windows en
sistemas críticos) el impacto habría sido mucho menor.

En este caso concreto la situación ha sido un poco extraña. Parece que el panel
de control al que se conectaba antes de mutar (durante los dos primeros días)
no ha estado operativo en ningún momento. Además la campaña original incluye un
“kill switch“, un dominio que, si el ransomware lo encuentra activo, no actúa.

Todo esto nos da lugar a la posibilidad de que este ransomware haya sido,
simplemente, una prueba de concepto que se ha ido de las manos.

Pese al daño causado, el hecho de que el malware haya tenido tanta publicidad y
relevancia ha podido servir de llamada de atención. ¡Por primera vez hemos
podido oír hablar del ransomware en las noticias y en los periódicos de todo el
mundo!

Parece que está llamada de atención ha conseguido algo que probablemente los
autores del ransomware no esperaban: efecto rebote.

![Wannacry dump](/images/wcry.png)

Aquí podemos ver un hexdump de la muestra de WCRY, mostrando las extensiones
afectadas. hexdump de la muestra de WCRY mostrando las extensiones afectadas

Podéis encontrar más información sobre el ataque en la web del ccn-cert.
Además, la mayoría de antivirus ya lo detectan, y es posible evitar la
infección vía SMB teniendo la actualización ms17-010.

Por otro lado, recordemos que han utilizado el exploit robado a la NSA por
ShadowBrokers, que se hizo publico en Marzo de 2017, hay hasta un port a
metasploit que podemos encontrar aquí
