import {_} from 'vue-underscore';
import Vue from '../../VueInstance';

export default class Add101Tech {
    createApp(element) {
        this.app = new Vue({
            'el': element,
            data: {
                selectedTech: []
            },
            beforeMount() {
                this.$on('addSelectedTech', function (tech) {
                    this.selectedTech.push(tech);
                });

                this.$on('changeSelectedTech', function (tech) {
                    this.selectedTech = _.reject(this.selectedTech, function(techId){ return techId === tech.oldValue; });
                    this.selectedTech.push(tech.newValue);
                });

                this.$on('removeSelectedTech', function (tech) {
                    this.selectedTech = _.reject(this.selectedTech, function(techId){ return techId === tech.oldValue; });
                });
            }
        });
    }
}
