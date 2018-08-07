export default class Tabs {
    constructor() {
        this.activeTab = 0;
    }

    setTab(i) {
        let tabs = document.querySelectorAll('#cardadd101 .tabs li');
        let panels = document.querySelectorAll('#cardadd101 .panels > div');
        tabs[this.activeTab].classList.remove('is-active');
        panels[this.activeTab].classList.add('is-hidden');
        tabs[i].classList.add('is-active');
        panels[i].classList.remove('is-hidden');
        this.activeTab = i;
    }

    nextTab() {
        let nextTab = this.activeTab + 1;
        if (nextTab > 4) {
            nextTab = 0;
        }
        this.setTab(nextTab);
    }
};
