import Vue from 'vue';
import store from './vuex'
import './filters'
import EventBus from "./services/Events.js";
require('./bootstrap');

window.Vue = require('vue').default;
Vue.mixin(require('./translations.js'))
Vue.prototype.$eventBus = EventBus;
Vue.component('video-editor-component', require('./views/VideoEditorComponent.vue').default);
Vue.component('acceptance-box-component', require('./components/AcceptanceBoxComponent.vue').default);
Vue.component('tabs-panel-component', require('./components/TabsPanelComponent.vue').default);
Vue.component('comment-form-component', require('./components/comments/CommentFormComponent.vue').default);
Vue.component('comments-list-component', require('./components/comments/CommentsListComponent.vue').default);
Vue.component('comment-box-component', require('./components/comments/CommentBoxComponent.vue').default);
Vue.component('work-time-tab-component', require('./components/WorkTimeTabComponent.vue').default);
Vue.component('attachment-form-component', require('./components/attachments/AttachmentFormComponent.vue').default);
Vue.component('attachments-list-component', require('./components/attachments/AttachmentsListComponent.vue').default);
Vue.component('to-do-list-component', require('./components/ToDoListComponent.vue').default);
Vue.component('projects-board-component', require('./views/ProjectsBoardComponent.vue').default);
Vue.component('clients-board-component', require('./views/ClientsBoardComponent.vue').default);
Vue.component('avatar-component', require('./components/AvatarComponent.vue').default);
Vue.component('to-do-modal-input', require('./components/ToDoModalInput.vue').default);
Vue.component('video-js', require('./components/VideoJS.vue').default);
Vue.component('marker-modal', require('./components/MarkerModal.vue').default);
const app = new Vue({
    el: '#app',
    store: store,
});