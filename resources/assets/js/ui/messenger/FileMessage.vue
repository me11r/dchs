<template>
    <a
        class="messenger-message-file"
        target="_blank"
        :href="file.filepath">
        <div class="icon">
            <i class="fas fa-file-alt fa-fw fa-2x"></i>
        </div>
        <div class="info">
            <div class="filename">{{ file.filename }}</div>
            <div class="size">{{ file.size }}</div>
        </div>
    </a>
</template>

<script>
export default {
    name: 'FileMessage',
    props: {
        message: {
            type: Object,
            default: () => {
                return {
                    id: 0,
                    message: '',
                    type: 'file'
                };
            }
        },
        file: {
            type: Object,
            default: () => {
                return {
                    id: 0,
                    filename: '',
                    filepath: '',
                    size: 0,
                    mime: ''
                };
            }
        }
    }
};
</script>

<style lang="scss">
    @import "../../../sass/variables";
    .messenger-message-file {
        flex-shrink: 1;
        display: flex;
        padding: 5px;
        border: 1px solid $blueish;
        border-radius: 4px;
        background-color: $blueish;
        text-decoration: none;
        &:hover {
            background-color: darken($blueish, 20%);
        }
        .icon {
            color: $primary;
            flex-grow: 0;
            margin: 8px;
            padding: 3px;
        }
        .info {
            flex-grow: 1;
        }
    }
</style>
