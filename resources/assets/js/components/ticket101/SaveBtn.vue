<template>
    <button type="submit" @click.prevent="onClickSubmit" class="button is-basic is-main"><i class="fas fa-check"></i>&nbsp;
        Сохранить
    </button>
</template>

<script>
    import {globalBus} from '../../scripts/global-bus';
    export default {
        name: "SaveBtn",
        // components:
        data: function(){
            return {
                canSubmit: true,
            }
        },
        methods:{
            onClickSubmit(){
                let form = document.getElementById('card-101-form');

                if(this.canSubmit){
                    // document.getElementById('preload_pane').style.display = 'block';
                    const loadingComponent = this.$loading.open({
                        container: form
                    })
                    form.submit();
                    this.canSubmit = false;
                }
            },
        },
        mounted(){
            let self = this;
            globalBus.$on('SAVING_CARD101', (event) => {
                let form = document.getElementById('card-101-form');
                const loadingComponent = self.$loading.open({
                    container: form
                })
            });
        }
    }

</script>

<style scoped>

</style>