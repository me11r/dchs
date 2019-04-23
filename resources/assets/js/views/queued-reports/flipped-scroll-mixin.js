export const FlippedScrollMixin = {
    data() {
        return {
            scrolledToBottom: false
        };
    },
    computed: {
        parentDivClasses() {
            return !this.scrolledToBottom ? 'flipped-scroll' : '';
        }
    },
    methods: {
        scroll () {
            window.onscroll = () => {
                this.scrolledToBottom = !!(Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight);
            };
        }
    },
    mounted() {
        this.scroll();
    }
};
