<template>
  <div class="ibox">
    <div class="ibox-title">
      <h5>{{ __('to_do') }}</h5>
      <div class="ibox-tools">
        <a class="collapse-link">
          <i class="fa fa-chevron-up"></i>
        </a>
      </div>
    </div>
    <div class="ibox-content small-padding">
      <draggable
        class="todo-list small-list"
        group="tasks"
        :list="tasks"
        @change="newOrder($event)"
      >
        <li v-for="task in tasks" :key="task.id">
          <input
            type="checkbox"
            v-model="task.checked"
            @change="check(task.id, task.checked)"
          />
          <span class="m-l-xs" :class="{ 'todo-completed': task.checked }">
            <a
              data-toggle="modal"
              data-target="#editTodo"
              :data-task="JSON.stringify(task)"
              @click="renderContent(task.id)"
            >
              <span class="to-do-list" v-html="urlify(task.content)"></span>
            </a>
          </span>
        </li>
      </draggable>
      <button
        data-toggle="modal"
        data-target="#addTodo"
        class="btn btn-default m-t-xs"
      >
        {{ __('add_position') }}
      </button>
    </div>
  </div>
</template>

<script>
import draggable from "vuedraggable";
import highlightUser from "../mixins/highlightUserMixin.js";
export default {
  name: "ToDoList",
  mixins: [highlightUser],
  display: "ToDoList",
  order: 1,
  components: {
    draggable,
  },
  props: ["propTasks", "project", "users"],
  data() {
    return {
      tasks: [],
    };
  },
  mounted() {
    this.tasks = this.propTasks;
  },
  methods: {
    check(id, value) {
      axios
        .post("/api/tasks/change", { id: id, value: value })
        .then((response) => {
          let logs = document.getElementById("history-box");
          logs.innerHTML = response.data.html;
        })
        .catch((error) => {});
    },
    renderContent(id) {
      axios
        .post("/api/tasks-by-id", { id: id })
        .then((response) => {
          let logs = document.getElementById("history-box");
          logs.innerHTML = response.data.html;
        })
        .catch((error) => {});
    },
    newOrder(evt) {
      axios
        .post("/api/tasks/change-order", {
          order: this.tasks,
        })
        .then((response) => {})
        .catch((error) => {});
    },
    urlify(text) {
      text = this.highlightUser(text);
      var urlRegex = /(https?:\/\/[^\s]+)/g;
      return text.replace(urlRegex, function (url) {
        return '<a href="' + url + '" target="_blank">' + url + "</a>";
      });
    },
  },
};
</script>
