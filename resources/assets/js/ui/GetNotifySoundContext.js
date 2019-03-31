import axios from 'axios';

class GetNotifySoundContext {
    constructor(url) {
        this.context = new AudioContext();
        this.loaded = false;
        this.url = url;
        this.loadpromise = this.load();
    }

    load() {
        return new Promise((resolve, reject) => {
            if (!this.loaded) {
                axios.get(this.url, {responseType: 'arraybuffer'}).then((response) => {
                    this.context.decodeAudioData(response.data, (buffer) => {
                        this.audio = this.context.createBufferSource();
                        this.audio.buffer = buffer;
                        this.audio.loop = false;
                        this.audio.connect(this.context.destination);
                        this.loaded = true;
                        resolve(this.audio);
                    });
                });
            } else {
                resolve(this.audio);
            }
        });
    }

    getContext() {
        return new Promise((resolve, reject) => {
            this.loadpromise.then(() => {
                resolve(this.audio);
            }).catch(error => {
                console.log('errr', error);
            });
        });
    }
}

export default function() {
    return new GetNotifySoundContext('/notify.wav');
};
