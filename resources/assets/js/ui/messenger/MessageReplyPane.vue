<template>
    <div class="reply-pane">
        <div class="attach-file">1</div>
        <div class="text"><textarea
            name="message-text"
            class="reply-textarea"></textarea></div>
        <div class="send-message">3 </div>
    </div>
</template>

<script>
export default {
    name: 'MessageReplyPane'
};
</script>

<style lang="scss" scoped>
.reply-pane {
    display: flex;
    min-height: 100px;
    height: 100px;
    box-sizing: border-box;
    .attach-file {
        flex-grow: 0;
        min-width: 100px;
        border-right: 1px solid cadetblue;
        box-sizing: border-box;
    }
    .text {
        flex-grow: 1;
        box-sizing: border-box;
        .reply-textarea {
            width: 100%;
            font-size: 16px;
            padding: 3px 5px;
            min-height: 100%;
            line-height: 1.1;
            border: none;
            box-sizing: border-box;
        }
    }
    .send-message {
        box-sizing: border-box;
        flex-grow: 0;
        max-width: 100px;
        width: 100%;
        border-left: 1px solid cadetblue;
    }
}
</style>
