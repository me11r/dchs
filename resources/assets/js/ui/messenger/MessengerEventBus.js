import Vue from 'vue';
let instance;
export default function() {
    if (!instance) {
        instance = new Vue();
    }
    return instance;
}
