<template>
    <div>
        <a
            class="messenger-message-file"
            target="_blank"
            :href="getUrl">
            <div class="icon">
                <i class="far fa-file fa-fw fa-2x"></i>
            </div>
            <div class="info">
                <div class="filename">{{ file.filename }}</div>
                <div class="size">{{ filesize }}</div>
            </div>
        </a>
    </div>
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
    },
    computed: {
        filesize: function() {
            const size = this.file.size;
            let i = (size === 0) ? 0 : (Math.floor(Math.log(size) / Math.log(1024)));
            return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['байт', 'килобайт', 'мегабайт', 'гигабайт', 'терабайт'][i];
        },
        getUrl: function() {
            return '/api/upload/file/download/' + this.file.id;
        }
    },
    methods: {
    }
};
</script>

<style lang="scss">
    @import "../../../sass/variables";
    .messenger-message-file {
        flex-shrink: 1;
        display: flex;
        padding: 5px;
        border: 1px solid $yellow;
        border-radius: 4px;
        background-color: lighten($yellow, 40%);
        text-decoration: none !important;
        margin: 4px;
        min-width: 300px;
        &:hover {
            background-color: lighten($yellow, 20%);
        }
        .icon {
            color: $primary;
            flex-grow: 0;
            margin: 3px;
            padding: 3px;
        }
        .info {
            margin-left: 1rem;
            font-size: 13px;
            flex-grow: 1;
            text-decoration: none;
        }
    }
</style>
