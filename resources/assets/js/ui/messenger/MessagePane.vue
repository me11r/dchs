<template>
<div class="message-pane" :class="activeClass">
    fsdgsdgsgsdgsgsgs
</div>
</template>

<script>
  export default {
    name: 'MessagePane',
    props: {
      me: {
        type: Object,
        default: ()=>{},
      },
      user: {
        type: Object,
        default: ()=>{},
      },
      selected: {
        type: Boolean,
        default: false
      }
    },

    computed: {
      activeClass: function() {
        return this.selected?"is-active":'';
      }
    }
  };
</script>

<style lang="scss" scoped>
    @import "../../../sass/variables";
    .message-pane {
        display: none;
        height: 100%;
        &.is-active {
            display: block;
            min-width: 600px;
            border-right: 1px solid $primary;
        }
    }
</style>
