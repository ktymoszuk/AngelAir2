<template>
</template>

<script>
import { emitter } from "../event-bus.js";

let conteggioBottoneMqtt = 0;

let a = {
    data(){
        return{
        }
    },
    methods:{
        //Push per la richiesta di aggiornamento
        updateMqtt(){
            try{
                emitter.emit("agg-mqtt",  conteggioBottoneMqtt++);
            } catch(e){
                console.log(e)
            }
        },
        //Push dei dati derivante dall' mqtt
        datiMqtt(obj){
            try{
                emitter.emit("dati-mqtt",  obj);
            } catch(e){
                console.log(e)
            }
        }
    }
};

export default a;

//Richiamo dell'mqtt
export function mqttListener(obj) {
    try {
        a.methods.updateMqtt();
        a.methods.datiMqtt(obj);
    } catch (e) {
        console.log(e);
    }
}
</script>