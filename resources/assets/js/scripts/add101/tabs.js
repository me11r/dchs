export default class Tabs {
    constructor () {
        this.activeTab = 0;
        this.isNewForm = (document.getElementById('card-101-form-id').getAttribute('data-id') === '0');
    }

    setTab (i) {
        if ((this.activeTab === 0) && (this.isNewForm === true) && (i !== 0)) {
            const form = document.getElementById('card-101-form');
            let valid = form.checkValidity();
            if (valid) {
                form.action += ('?comeback=' + i);
                return form.submit();
            } else {
                form.querySelector('button[type=submit]').click();
            }
            return false;
        }

        let tabs = document.querySelectorAll('#cardadd101 .tabs li');
        let panels = document.querySelectorAll('#cardadd101 .panels > div');
        tabs[this.activeTab].classList.remove('is-active');
        panels[this.activeTab].classList.add('is-hidden');
        tabs[i].classList.add('is-active');
        panels[i].classList.remove('is-hidden');
        this.activeTab = i;
    }

    nextTab () {
        let nextTab = this.activeTab + 1;
        if (nextTab > 4) {
            nextTab = 0;
        }
        this.setTab(nextTab);
    }
};
