import React from "react";
import ReactDOM from "react-dom/client";

import "./echo";

import WebsocketDemo from "./components/WebsocketDemo";

const root = ReactDOM.createRoot(document.getElementById("app"));

root.render(
    <React.StrictMode>
        <WebsocketDemo />
    </React.StrictMode>
);