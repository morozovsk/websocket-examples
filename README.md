Examples for simple websocket server on php https://github.com/morozovsk/websocket.

"composer require morozovsk/websocket-examples"

Examples directory:
* chat - simple chat (single daemon) (live demo: http://sharoid.ru/chat.html )
* chat2 - chat (master + worker) (live demo: http://sharoid.ru/chat2.html )
* chat3 - chat (single daemon + script who can send personal message by clientId, userId or PHPSESSID)
* yii - sample of use yii with websockets: Yii::app()->websocket->send('Hello world');
* game - simple game (live demo: http://sharoid.ru/game.html )
* game2 - game (live demo: http://sharoid.ru/game2.html ) (port of node.js game: https://github.com/amikstrike/wn)
*

Run from console:
* start: "php index.php start" or "nohup php index.php start &"
* stop: "php index.php stop"
* restart: "php index.php restart" or "nohup php index.php restart &"

###License

(The MIT License)

Copyright (c) 2014 Vladimir Goncharov

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the 'Software'), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
