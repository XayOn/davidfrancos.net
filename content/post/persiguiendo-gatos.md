+++
author = "David Francos"
title = "Persiguiendo gatos con AckAck"
date = "2022-03-22"
description = "Usando una aspiradora WeBack (Neatsvor) con una camara para perseguir a los gatos"
summary = "Montando un robot aspirador para perseguir a los gatos con una aspiradora Neatsvor (WeBack), y una camara xiaomi hackeada"

tags = [
    "python",
    "neatsvor",
    "weback",
]
+++

¿Que hacemos cuando nos aburrimos? ¡Molestar a nuestras mascotas!

{{< figure title="Impuesto gatuno" src="https://github.com/XayOn/ackack/raw/master/docs/screenshot.png">}}

> **Descargo de responsabilidad**: Ningun gatito ha sido herido o
> estresado en este articulo. Ninguna de las gatas tiene miedo realmente
> a la aspiradora.

Entrando en modo reciclaje en casa, me he topado con una vieja [Yi Home
Camera](https://xiaomipedia.com/p/yi-home-camera/). Hace tiempo que tenia
intención de hacer algo con el robot aspirador y una camara web, así que, con
un poco de celo de doble cara (la solución a todos los problemas) y un poco de
maña con el software, he acabado con improvisado sistema de videovigilancia
movil.

## El software

La mayoría de mis desarrollos son con aiohttp, y API-first con OpenAPI (gracias
a [hh-h](https://github.com/hh-h/aiohttp-swagger3) por su libreria de openapi3,
sin embargo, para algo tan simple como voy a hacer esto, no voy ni a meterle

## Interactuando con el robot

Primero vamos a montar una sencilla API de control para el robot.
Los robots de NeatsVor con WeBack tienen
una libreria no-oficial en python que se comunica con el MQTT de amazon con el
que se controlan estos bichos.

Si lo abrimos, además, veremos que por dentro
lleva un **ESP-32** para darle la conectividad wifi y bluetooth (y,
seguramente, el mqtt vaya ahí)

La librería
[weback_unofficial](https://github.com/opravdin/weback-unofficial)
expone una clase CleanRobot que nos
permite acceder tanto a una serie de
metodos de alto nivel como directamente
a metodos para publicar en el mqtt.

Sin embargo, lo que quiero aquí es que el robot **se mueva**, y esta libreria
esta mas bien pensada para que el robot aspirador, bueno... aspire.

Por lo que, al final, he etendido la clase del robot para añadirle movimiento

{{<highlight python>}}
class MovableRobot(CleanRobot):
    """Custom CleanRobot subclass allowing movements"""

    def move(self, position: str, time: int | float = 1):
        """Helper function to move to a position, then stop

        This is because movements (except up) are not "move to" but "move
        in that direction", so move left would make it keep spinning to the
        left
        """
        self.publish_single('working_status', f'Move{position.upper()}')
        if position in ('left', 'right', 'back', 'down', 'stop'):
            sleep(time)
            self.publish_single('working_status', 'MoveStop')

{{</highlight>}}

> Ojo, este código solo funciona con python3.10

Ahora, simplemente levantamos una API que exponga estos metodos, y un pequeño html + js.
Este codigo utiliza FastAPI, aunque estoy en proceso de migrar el proyecto a un
framework con el que me siento más comodo, aiohttp.

Le añadimos la BASE_URL para poder utilizarlo con un proxy inverso tipo traefik
con docker.

{{<highlight python>}}

#: Setup BASE URL
BASE = os.getenv('BASE_URL', '')
RPRE = {'prefix': BASE} if os.getenv('BASE_URL') else {}
router = APIRouter(**RPRE)

app = FastAPI()

# Distribute statics (vuejs app)
app.mount(f"{BASE}/static", StaticFiles(directory="static"), name="main")

if __name__ == "__main__":
    print(f"Starting with parameters {RPRE}")
    Status.robot = init_robot(os.getenv('WEBACK_USERNAME'),
                              os.getenv('WEBACK_PASSWORD'))


@router.get("/", response_class=HTMLResponse)
async def index():
    return Path('static/index.html').read_text()


@router.get("/move/")
async def move(movement: str = Query(None)):
    """Move robot"""
    if not Status.robot:
        Status.robot = init_robot(os.getenv('WEBACK_USERNAME'),
                                  os.getenv('WEBACK_PASSWORD'))

    if not movement:
        return []

    if movement in ('left', 'right', 'up', 'down', 'back'):
        Status.robot.move(movement)
    else:
        # Don't do the whole "move, wait 1s, stop moving" except on positional
        # movements
        getattr(Status.robot, movement)()
    return {"status": "sent"}
app.include_router(router)
{{</highlight>}}

Con esto, lo instalamos con poetry, y podemos arrancarlo con unicorn asi:

{{<highlight bash>}}
poetry install
uvicorn --host="0.0.0.0" --port=8080 ackack:app
{{</highlight>}}

Y finalmente, podemos usar la API:

{{<highlight bash>}}
curl http://localhost:8080/move/?movement=left
{{</highlight>}}

Ahora, con un javascript tan sencillo como este:
{{<highlight javascript>}}
      function move(pos){ axios.get(`move/?movement=${pos}`); }
      const MAPS = {39: 'right', 37: 'left', 38: 'up', 40: 'down', 8: 'stop', 13: 'turn_on'}
      window.onkeydown = function(ev){ let code = MAPS[ev.keyCode]; if (code){ move(code); } }
{{</highlight>}}

Dispondremos de una función move, que será invocada cuando

Por ultimo, solo tenemos que configurar videojs, y arrancar ffmpeg apuntando a la IP de nuestra webam.
El problema es que nadie soporta RTSP de forma nativa, asi que tenemos que convertirlo. Lo vamos a convertir a m3u8 con

{{<highlight bash>}}
ffmpeg -i <URL RTSP> -y -c:a aac -b:a 160000 -ac 2 -s 854x480 -c:v libx264 -b:v 800000 -hls_time 10 -hls_list_size 10 -start_number 1 static/playlist.m3u8 &
{{</highlight>}}

Y finalmente, configuramos la parte web:

{{<highlight html>}}
      <head>
        <link href="https://unpkg.com/video.js/dist/video-js.css" rel="stylesheet">
        <script src="https://unpkg.com/video.js/dist/video.js"></script>
        <script src="https://unpkg.com/videojs-contrib-hls/dist/videojs-contrib-hls.js"></script>
        <script>
          window.addEventListener('load', function() { videojs('cam').play(); });
        </script>
      </head>
      <body>
        <video id="cam" class="video-js vjs-fluid vjs-default-skin" controls preload="auto" data-setup='{}'>
          <source src="static/playlist.m3u8" type="application/x-mpegURL">
        </video>
      </body>
{{</highlight>}}

Con esto ya podremos ver por pantalla la salida de la webcam, ¡Y controlar con
las flechas el robot!


En el [repositorio](https://github.com/XayOn/ackack) esta todo el codigo,
incluido dockerfile, para que lo podais cotillear, y en dockerhub teneis la
imagen con instrucciones de uso, gracias por haber leído hasta aquí!
