import { getRequest } from  "./ax-request.js";
import Paho from 'paho-mqtt';
import { mqttListener } from "./vue/mqtt.vue";

let mqtt;

//callback function
function onConnect() { 
  try{
    // mqtt.subscribe('/angeltracking/dati');
    // mqtt.subscribe('/angeltracking/missioni');
    mqtt.subscribe('/dati');
    mqtt.subscribe('/missioni');
  }catch(e){
    console.log(e);
  }
}

function onMessageArrived(msg) {
  try{
    let obj = JSON.parse(msg.payloadString);
    // visualizzazione messaggio
    mqttListener(obj);
  }catch(e){
    console.log(e);
  }
}

function onFailure(message) {
  try{
    setTimeout(MQTTconnect, 3000);
  }catch(e){
    console.log(e);
  }
}

function onConnectionLost(e) {
  try{
    MQTTconnect();
  }catch(e){
    console.log(e);
  }
}

function MQTTconnect() {
  try{
    // let ws = getRequest('/angeltracking/mqtt/log', null, null); // client id
    let ws = getRequest('/mqtt/log', null, null); // client id
    mqtt = new Paho.Client(ws.ws_host, parseInt(ws.ws_port), Math.random(10).toString());
    let options = { useSSL: true, timeout: 3, userName: ws.ws_username, password: ws.ws_password, onSuccess: onConnect, onFailure: onFailure, };
  
    mqtt.onConnectionLost = onConnectionLost;
    mqtt.onMessageArrived = onMessageArrived;
  
    mqtt.connect(options); //connect
  }catch(e){
    console.log(e);
  }
}

MQTTconnect()