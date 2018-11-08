export default class Tabs {
    constructor () {
        this.activeTab = 0;
        this.tabsCount = 0;
        this.isNewForm = (document.getElementById('card-101-form-id').getAttribute('data-id') === '0');
    }

    setTab (i) {
        if (!this.isNext(i)) return false;

        const form = document.getElementById('card-101-form');
        let valid = form.checkValidity();
        let fire_department_id = document.getElementById('fire_department_id');

        if ((this.activeTab === 0) && (this.isNewForm === true) && (i !== 0)) {
            if (valid) {
                form.action += ('?comeback=' + i);
                return form.submit();
            } else {
                if (fire_department_id.value === undefined || fire_department_id.value == false) {
                    return false;
                }
                form.querySelector('button[type=submit]').click();
            }
            return false;
        } else {
            if (valid) {
                // var form = document.getElementById('card-101-form');
                var data = new FormData(form);
                let axios = require('axios');
                let id = document.getElementById('card-101-form-id').dataset.id;
                axios.post('/card/add101/' + id, data).catch((resp) => {
                    console.dir(resp.response.data.message);
                });
            } else {
                form.querySelector('button[type=submit]').click();
            }
        }
        if (i !== 6) {
            form.querySelector('button[type=submit]').classList.add('is-hidden');
        } else {
            form.querySelector('button[type=submit]').classList.remove('is-hidden');
        }

        let tabs = document.querySelectorAll('#cardadd101 .tabs li');
        let panels = document.querySelectorAll('#cardadd101 .panels > div');
        this.tabsCount = panels.length;
        tabs[this.activeTab].classList.remove('is-active');
        panels[this.activeTab].classList.add('is-hidden');
        tabs[i].classList.add('is-active');
        panels[i].classList.remove('is-hidden');
        this.activeTab = i;
    }

    nextTab () {
        let nextTab = this.activeTab + 1;
        if (nextTab >= this.tabsCount) {
            nextTab = 0;
        }
        this.setTab(nextTab);
    }

    isNext(step) {
        if (step === 1) {
            if (!document.getElementById('location').value && !document.getElementById('fireplace').value) { return false; }
        }
        return true;
    }
};
