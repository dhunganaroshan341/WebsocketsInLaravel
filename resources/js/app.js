import "./echo";

console.log("🚀 App started");

window.Echo.channel("hello-world")
    .listen("HelloWorldEvent", (e) => {
        console.log("🎉 Event received!", e);
    });