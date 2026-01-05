# Introducción

Queria estrenarme con **BDD (Behavior Drive Development)** en PHP y esta Kata de [Snakes And Ladders](https://agilekatas.co.uk/) es una buena practica para ello en la que las necesidades de negocio descritas como _historias de usuario_ marcan el flujo de tests con los que se va resolviendo.

En PHP esto se puede llevar a cabo usando el framework [**Behat,**](https://docs.behat.org/en/latest/) que trabaja a partir de historias de usuario (Features) y test de aceptación (Scenarios) descritos con lenguaje Gherkin.

También he usado **PHPUnit** que es el framework habitual en PHP para test unitarios y TDD. En este caso Behat guía el desarrollo de cada Feature y PHPUnit da soporte en aplicar tests unitarios a determinados elementos.

# Estructura del proyecto

Esta versión solo resuelve la __Feature 1__ de la kata original pero es suficiente para ver como trabajar con _Behat_ y _BDD_ que al final es mi objetivo en este proyecto.

A nivel de estructura de la aplicación esta tiene dos partes facilmente identificables viendo el código.

* La libreria que implenta las funcionalidades detalladas por las 3 historias de usuario y sus tests

* Una pequeña aplicación de consola que haciendo uso de los componentes de la libreria permite jugar simulando las acciones descritas por cada US

Todo el código se encuentra dentro de _src:_

* **src/Game** contiene la classe que forma el core de la aplicación de consola

* **src/Lib** contiene las 4 classes que forman el core del backend, de la libreria y que se ajustan a cada US y sus correspondientes UAT

# Desarrollo de la Kata

Los test de aceptación de cada historia de usuario guían la realización de esta kata. Las historias de usuario indican lo que se espera en cada fase y queda claro que se trata de implementar solo las funcionalidades requeridas por ellas. Ni una más ni una menos para tener en verde todos los tests.

Para centrarme en la _Feature 1_ de la kata original he tratado cada Historia de usuario como una _Feature_ en _Behat_. Este es el detalle de cada una de ellas: 

## US 1 - Token Can Move Across the Board

Todo empieza con añadir en el fichero _us1-move-across-board.feature_ toda la descripción en Gherkin de esta primera US.

````gherkin
Feature: US 1 - Token Can Move Across the Board
  As a player
  I want to be able to move my token
  So that I can get closer to the goal

  Scenario: UAT1 Start the game
    Given the game is started
    When the token is placed on the board
    Then the token is on square 1

  Scenario: UAT2 Token on square 1
    Given the token is on square 1
    When the token is moved 3 spaces
    Then the token is on square 4

  Scenario: UAT3 Token on square 8
    Given the token is on square 1
    When the token is moved 3 spaces
    And then it is moved 4 spaces
    Then the token is on square 8
````

A partir de aquí Behat con:

````
$ behat/bin --append-snippets
````

Se generan automáticamente los métodos dentro de _bootstrap/FeatureContext.php_ que corresponden a cada linea _Given/When/And/Then_ de cada UAT. Los metodos estan vacios, solo actuan como punto de entrada, y se trata de ir uno por uno aplicando el código necesario para pasar el test.

El proceso es ejecutar _bin/behat_ ver todos los test en rojo, proceder a resolver UAT por UAT implementando el mínimo código para pasar el test, tener test en verde y refactorizar. 

Estare repitiendo este ciclo durante las 3 user stories, y creando 3 ficheros _.feature_ con las user stories y los test de aceptación, para que Behat los procese para ir ejecuntando los tests.

En esta primera US veo necesario tener ya la class _Game_ que da sentido a _Given the game is started_ y será el punto de inicio de cualquier partida. Aparece también la class _Token_ con la que moverse por el tablero.

## US 2 - Player Can Win the Game

Aquí la cosa ya se pone más interesante, _Player_ cobra más importancia en los test de aceptación de esta user story, por lo que decido crear una class _Player_ que es la que mantiene el _estado_ del jugador y a su vez lo mueve por el tablero mediante _Token_ Esto implica también refactorizar _Game_ para que haga una instancia de _Player_ en vez de _Token_ A partir de este momento el juego arranca con un _Player_ que a su vez dispone de su propio _Token_

_Game_ adquiere también más importancia concentro en ella las _reglas del juego_, el check de si el jugador gana o no.

Esta combinación de _Game_/_Player_/_Token_ permite resolver los 2 test de aceptación y a la vez mantener responsabilidades separadas, mientras el resto de tests de la user story 1 se mantienen también en verde.

## US 3 - Moves Are Determined By Dice Rolls

En este paso creo una nueva class _Dice_. Separo de esta manera la responsabilidad de generar una tirada de dados. _Player_ en este momento es la class que asume el control de _Token_ y de _Dice_

Sigo usando _Asserts_ de PHPUnit para controlar resultados concretos dentro de un método que responde a una acción de un test de aceptación, por ejemplo si el valor de los dados está dentro de un rango calculado:

````php
/**
 * @Then the result should be between :arg1-:arg2 inclusive
 */
public function theResultShouldBeBetweenInclusive($arg1, $arg2)
{
    $sides = range($arg1, $arg2);
    Assert::assertContains($this->diceresult, $sides);
}
````
O para confirmar que realmente el movimiento del token ha correspondido con el número de pasos indicados por el dado:

````php
/**
 * @Then the token should move :arg1 spaces
 */
public function theTokenShouldMoveSpaces($arg1)
{
    $old = $this->player->getOldPosition();
    $new = $this->player->getPosition();

    $rslt = $new-$old;

    Assert::assertEquals($arg1, $rslt);
    
}
````

Finalizando esta tercera user story, todos los test de aceptación de cada una de ellas pasan en verde.

# Desarrollo de la aplicación de consola

La aplicación de consola actua como un frontend para poner a prueba la libreria. Para su desarrollo he usado el componente _Console_ del Symfony Framework, que permite disponer de los elementos basicos para crear una aplicación de consola, gestiónar input via parámetros o teclado y gestionar su output.

En _src/Game/GameCommand.php_ se encuentra el core de la aplicación de consola. Es una class que hereda del la _class_ _Command_ de Symfony y sobreescribe dos metodos: _configure_ donde especificamos los parámetros que aceptara la aplicación, instrucciones, etc. y _execute_ que es el metodo encargado de su funcionamiento.

Esta class esta ya haciendo uso de la libreria con el core del juego. 

```php
use SnakesAndLadders\Lib\Game;
```
Con este componente ya puede iniciar el juego, el jugador, moverlo y lanzar dados.

Este enfoque modular permite separar el backend del frontend y por otra parte el código queda más desacoplado, con responsabilidades muy concretas para cada componente (_class_) lo que facilita los test y el mantenimiento.

Ha sido mi primera vez con Behat y un proceso muy básico de BDD pero me ha gustado esta kata porque obliga a desarrollar unas funcionalidades sin salirse de lo que se pide en las historias de usuario y generando test de aceptación que dejan cubiertas todas la peticiones de negocio.

# Un apunte sobre los tests

Usando _Behat_ el código de los test va todo en _bootstrap/FeatureContext.php_, localizar las sentencias _Give/When/Then/And_ debe hacerse mirando los comentarios. _Behat_  usa _PHPDOC_ para indicar cada sentencia y su parametrización.

Luego genera nombres de metodos de acuerdo a la sentencia correspondiente.

Behat se apoya en los comentarios para identificar y controlar el comportamiento de cada sentencia.

En la salida de los tests tambien indica el nombre del metodo que resuelva cada sentencia de un escenario.

Tambien puede configurase _Behat_ para usar _contextos_ diferentes y de esta manera no queda todo en un solo _bootstrap/FeatureContext.php_ sino que se puede repartir en varios ficheros lo que permitiria, por ejemplo, tener historias de usuario en contextos diferentes o tipos de test diferentes para diferentes contextos. Pero esta es mi primera vez con Behat y no he querido complicarme a este punto por eso estan todos en _bootstrap/FeatureContext.php_

# Ejecutar el proyecto 

Si quieres probarlo puedes usar Docker.

Si no tienes docker en tu sistema lo puedes instalar [con estas instrucciones](https://docs.docker.com/get-docker/) 

## Ejecutar el Docker y arrancar el entorno

En tu terminal clona este repositorio y luego muevete dentro del directorio _SnakesAndLadders_ y ejecuta:

```
$ make build
```

Esto construye la imagen y arranca el contenedor _docker_ en el que ejecuta _composer_ para descargar todas las librerias necesarias.

Cuando termine puedes:

### Lanzar los test

```
$ make test
```
Esto te mostrara algo similar a:

```gherkin
Feature: US 1 - Token Can Move Across the Board
  As a player
  I want to be able to move my token
  So that I can get closer to the goal

  Scenario: UAT1 Start the game           # features/us1-move-across-board.feature:6
    Given the game is started             # FeatureContext::theGameIsStarted()
    When the token is placed on the board # FeatureContext::theTokenIsPlacedOnTheBoard()
    Then the token is on square 1         # FeatureContext::theTokenIsOnSquare()

  Scenario: UAT2 Token on square 1   # features/us1-move-across-board.feature:11
    Given the token is on square 1   # FeatureContext::theTokenIsOnSquare()
    When the token is moved 3 spaces # FeatureContext::theTokenIsMovedSpaces()
    Then the token is on square 4    # FeatureContext::theTokenIsOnSquare()

  Scenario: UAT3 Token on square 8   # features/us1-move-across-board.feature:16
    Given the token is on square 1   # FeatureContext::theTokenIsOnSquare()
    When the token is moved 3 spaces # FeatureContext::theTokenIsMovedSpaces()
    And then it is moved 4 spaces    # FeatureContext::thenItIsMovedSpaces()
    Then the token is on square 8    # FeatureContext::theTokenIsOnSquare()

Feature: US 2 - Player Can Win the Game
  As a player
  I want to be able to win the game
  So that I can gloat to everyone around

  Scenario: UAT1 Won the game        # features/us2-player-can-win-game.feature:6
    Given the token is on square 97  # FeatureContext::theTokenIsOnSquare()
    When the token is moved 3 spaces # FeatureContext::theTokenIsMovedSpaces()
    Then the token is on square 100  # FeatureContext::theTokenIsOnSquare()
    And the player has won the game  # FeatureContext::thePlayerHasWonTheGame()

  Scenario: UAT2 Not won the game       # features/us2-player-can-win-game.feature:12
    Given the token is on square 97     # FeatureContext::theTokenIsOnSquare()
    When the token is moved 4 spaces    # FeatureContext::theTokenIsMovedSpaces()
    Then the token is on square 97      # FeatureContext::theTokenIsOnSquare()
    And the player has not won the game # FeatureContext::thePlayerHasNotWonTheGame()

Feature: US 3 - Moves Are Determined By Dice Rolls
  As a player
  I want to move my token based on the roll of a die
  So that there is an element of chance in the game

  Scenario: UAT1 Dice result should be between 1-6 inclusive # features/us3-moves-determined-by-dice.feature:6
    Given the game is started                                # FeatureContext::theGameIsStarted()
    When the player rolls a die                              # FeatureContext::thePlayerRollsA()
    Then the result should be between 1-6 inclusive          # FeatureContext::theResultShouldBeBetweenInclusive()

  Scenario: UAT2 Player rolls a 4       # features/us3-moves-determined-by-dice.feature:11
    Given the player rolls a 4          # FeatureContext::thePlayerRollsA()
    When they move their token          # FeatureContext::theyMoveTheirToken()
    Then the token should move 4 spaces # FeatureContext::theTokenShouldMoveSpaces()

7 scenarios (7 passed)
24 steps (24 passed)
0m0.12s (9.36Mb)

```

Indicando que la Libreria desarrollada pasa todo los test de cada US.

Ya tienes el entorno en funcionamiento y has podido comprobar que todos los tests estan en verde! :clap:

### Ejecutar la aplicación 

En la raiz del proyecto tienes **game.php** que es el punto de entrada a la aplicación de consola, se ejecuta como un script php.

El juego ahora funciona al completo, y solo. Cuando ejecutes el comando _make run_ empezara y continuara realizando lanzamientos de dados y movimientos del jugador hasta hacerlo ganar.

```
$ make run
```

El resultado sera algo parecido a esto:

```
Dice show: 6
Player move token 6 squares
Player at square: 99
Player at snake square, moved to new position 80

Dice show: 6
Player move token 6 squares
Player at square: 86

Dice show: 4
Player move token 4 squares
Player at square: 90

Dice show: 1
Player move token 1 squares
Player at square: 91

Dice show: 6
Player move token 6 squares
Player at square: 97

Dice show: 6
Player can't move
Player at square: 97

Dice show: 4
Player can't move
Player at square: 97

Dice show: 6
Player can't move
Player at square: 97

Dice show: 5
Player can't move
Player at square: 97

Dice show: 3
Player move token 3 squares
Player at square: 100
Player WIN!!!!
```

Tambien la opción __run-bysteps__. Usando esta opción el juego, por cada lanzamiento de dados y movimiento, te preguntara si deseas continuar. 

Pulsado la tecla Y + [intro] continuas, y pulsando la tecla N +[intro] el juego termina en ese punto. Esto te permite ver paso a paso com va jugando:

```
$ make run-bysteps

Player at square: 1

Dice show: 5
Player move token 5 squares
Player at square: 6
Roll Dice ? [y/n] y
```

La aplicación ahora controla si el token del jugador cae en una de las casillas de _Serpientes_ o _Escaleras_:

````
Dice show: 6
Player move token 6 squares
Player at square: 99
Player at snake square, moved to new position 80
````

Esto es todo, para ver un resumen de los comandos disponibles con _make_ puedes hacer:

```
$ make help
```

