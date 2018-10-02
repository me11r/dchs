<template>
    <div class="container">
        <h4 class="title" style="padding: 3px 15px">Сведения
            о чрезвычайных ситуациях природного и техногенного характера произошедших
            на территории  ДЧС города Алматы
            по форме ЧС-1 за  август 2018 года
        </h4>
        <div class="panel">
            <table class="formation-record-table">
                <thead>
                <tr>
                    <th rowspan="2">Наименование ЧС</th>
                    <th colspan="3">Количество ЧС</th>
                    <th colspan="3">Пострадало всего, чел.</th>
                    <th colspan="3">из них:
                        погибло, чел</th>
                    <th>2017г.</th>
                    <th>2018г.</th>
                    <th>% (+,-)</th>
                    <th>2017г.</th>
                    <th>2018г.</th>
                    <th>% (+,-)</th>
                    <th>2017г.</th>
                    <th>2018г.</th>
                    <th>% (+,-)</th>
                </tr>
                </thead>

            </table>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        name: "MainPageReport",
        props: {
            date_begin: {
                type: String,
                default: '',
            },
            date_end: {
                type: String,
                default: '',
            },
            reasons: {
                type: Object,
                default: {}
            }
        },
        data: function () {
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                date_begin_: this.date_begin,
                date_end_: this.date_end,
                report_summary: {},
                reason_id: null,
                reasons_: this.reasons,
            }
        },
        methods: {
            selectReason(event) {

                this.reason_id = event.target.value;

                window.history.pushState('page2', 'Title', '/reports/101/emergency?reason=' + this.reason_id);

                // console.dir(this.reason_id);

                this.post_data();
            },
            print() {
                window.print();
            },
            post_data() {
                let self = this;
                axios.post('/reports/101/emergency', {
                    date_begin: self.date_begin_,
                    date_end: self.date_end_,
                    reason_id: self.reason_id,
                }).then((resp) => {
                    self.report_summary = resp.data;
                    console.dir(self.report_summary);
                });
            },

            selectPeriod() {
                this.post_data();
            }
        },

        created () {
            const token = document.head.querySelector('meta[name="csrf-token"]');
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content || '';
        }
    }
</script>

<style scoped>

</style>