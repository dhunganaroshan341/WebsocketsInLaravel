# Laravel Reverb + Echo + WebSockets (Learning Notes)

## Goal

Build a simple real-time communication system using:

* Laravel 13
* Laravel Reverb
* Laravel Echo
* Pusher Protocol
* Vite

The objective was **not** to build a chat application yet, but to understand how every component communicates internally.

---

# Architecture

```
Browser
    │
    ▼
Laravel Echo
    │
(Pusher Protocol)
    │
    ▼
Laravel Reverb
    ▲
    │
Laravel Application
```

The browser never communicates directly with Laravel after the WebSocket connection is established.

Instead:

```
Browser <--WebSocket--> Reverb

Laravel ----Broadcast Event----> Reverb
```

Reverb then pushes messages to every subscribed browser.

---

# Installation

Install Reverb

```bash
composer require laravel/reverb
```

Run the installer

```bash
php artisan reverb:install
```

Install frontend packages

```bash
npm install laravel-echo pusher-js
```

---

# Environment Configuration

```
BROADCAST_CONNECTION=reverb

QUEUE_CONNECTION=database

REVERB_APP_ID=...

REVERB_APP_KEY=...

REVERB_APP_SECRET=...

REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

---

# Echo Configuration

Create

```
resources/js/echo.js
```

```javascript
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "reverb",

    key: import.meta.env.VITE_REVERB_APP_KEY,

    wsHost: import.meta.env.VITE_REVERB_HOST,

    wsPort: import.meta.env.VITE_REVERB_PORT,

    wssPort: import.meta.env.VITE_REVERB_PORT,

    forceTLS: false,

    enabledTransports: ["ws"],
});
```

---

# Load Echo

Inside

```
resources/js/app.js
```

```javascript
import "./echo";

console.log("🚀 App started");

window.Echo.channel("hello-world")
    .listen("HelloWorldEvent", (event) => {
        console.log("🎉 Event received!", event);
    });
```

This imports Echo, opens the WebSocket connection and subscribes to the channel.

---

# Broadcast Event

```php
class HelloWorldEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function broadcastOn(): array
    {
        return [
            new Channel('hello-world'),
        ];
    }
}
```

Important:

The event **must implement** `ShouldBroadcast` (or `ShouldBroadcastNow`).

Otherwise Laravel only dispatches a normal event and nothing is broadcast.

---

# Triggering the Event

```php
Route::get('/hello', function () {

    event(new HelloWorldEvent());

    return 'Done!';
});
```

Visiting

```
/hello
```

dispatches the broadcast.

---

# Running Everything

Terminal 1

```bash
php artisan serve
```

Terminal 2

```bash
php artisan reverb:start
```

Terminal 3

```bash
npm run dev
```

(Optional)

Terminal 4

```bash
php artisan queue:work
```

---

# Important Discovery

Do **not** open `/hello` in the same browser tab.

Why?

Because `/hello` returns plain text.

The browser destroys the current page, unloading JavaScript.

This also destroys:

```
window.Echo
```

Instead:

Keep

```
/
```

open in one tab.

Open

```
/hello
```

in another tab.

The first tab stays connected and receives the event.

---

# What Actually Happens

```
Browser

↓

app.js executes

↓

echo.js executes

↓

Echo object created

↓

Pusher protocol starts

↓

TCP Connection

↓

HTTP Upgrade Request

↓

101 Switching Protocols

↓

WebSocket Established

↓

Subscribe to hello-world

↓

Laravel Route

↓

event(new HelloWorldEvent())

↓

Broadcast Manager

↓

Reverb

↓

Find all subscribers of hello-world

↓

Push event to browser

↓

console.log("🎉 Event received!")
```

---

# Things Learned

* WebSockets are persistent TCP connections.
* Reverb is a standalone WebSocket server.
* Echo is a frontend client library.
* Echo speaks the Pusher protocol.
* Reverb understands the Pusher protocol.
* Laravel broadcasts events to Reverb.
* Reverb pushes messages to subscribed clients.
* No HTTP polling is required after the WebSocket connection is established.

---

# Common Mistakes

❌ Forgetting to import `echo.js`.

❌ Forgetting to implement `ShouldBroadcast`.

❌ Setting `BROADCAST_CONNECTION=log`.

❌ Visiting `/hello` in the same browser tab.

❌ Forgetting to run Reverb.

❌ Forgetting to run Vite.

❌ Forgetting the queue worker when using queued broadcasts.

---

# Next Topics

* Public Channels
* Private Channels
* Presence Channels
* Channel Authorization
* routes/channels.php
* Authentication over WebSockets
* Broadcasting to specific users
* Building a production-ready chat application
* Typing indicators
* Read receipts
* Online/offline presence
* Scaling Reverb with Redis
* Multi-server deployments
* Production architecture
