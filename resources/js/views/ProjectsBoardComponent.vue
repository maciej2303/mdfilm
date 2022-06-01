<template>
  <div class="reverse">
    <div class="row">
      <div class="col-6 col-md-2 m-b-sm">
        <label class="m-t-xs">{{ __('status') }}</label>
        <multiselect
          v-model="projectStatusFilter"
          :options="projectStatusesInFilter"
          :multiple="true"
          label="localized_value"
          track-by="id"
          value="model_id"
          :show-labels="false"
          placeholder="Filtruj"
          @input="filterProjects"
          :taggable="true"
          @tag="addTag($event, projectStatusFilter, projectStatusesInFilter)"
          searchable
          tag-placeholder="Dodaj nowy tag"
        ></multiselect>
      </div>
      <div class="col-6 col-md-2 m-b-sm">
        <label class="m-t-xs">{{ __('project') }}</label>
        <multiselect
          v-model="projectFilter"
          :options="projects"
          :multiple="true"
          label="name"
          track-by="id"
          value="name"
          :show-labels="false"
          placeholder="Filtruj"
          @input="filterProjects"
          :taggable="true"
          @tag="addTag($event, projectFilter, projects)"
          searchable
          tag-placeholder="Dodaj nowy tag"
        ></multiselect>
      </div>
      <div class="col-6 col-md-2 m-b-sm">
        <label class="m-t-xs">{{ __('client') }}</label>
        <multiselect
          v-model="clientFilter"
          :options="clients"
          :multiple="true"
          label="name"
          track-by="id"
          value="name"
          :show-labels="false"
          placeholder="Filtruj"
          @input="filterProjects"
          :taggable="true"
          @tag="addTag($event, clientFilter, clients)"
          searchable
          tag-placeholder="Dodaj nowy tag"
        ></multiselect>
      </div>
      <div class="col-6 col-md-2 m-b-sm">
        <label class="m-t-xs">{{ __('team') }}</label>
        <multiselect
          v-model="userFilter"
          :options="users"
          :multiple="true"
          label="name"
          track-by="id"
          value="name"
          :show-labels="false"
          placeholder="Filtruj"
          @input="filterProjects"
          :taggable="true"
          @tag="addTag($event, userFilter, users)"
          searchable
          tag-placeholder="Dodaj nowy tag"
        ></multiselect>
      </div>
      <div class="col-6 col-md-2 m-b-sm">
        <label class="m-t-xs">{{ __('priority') }}</label>
        <multiselect
          v-model="priorityFilter"
          :options="priorities"
          :multiple="true"
          label="name"
          track-by="value"
          value="value"
          :show-labels="false"
          placeholder="Filtruj"
          @input="filterProjects"
          searchable
        ></multiselect>
      </div>
      <div class="col-6 col-md-2 m-b-sm">
        <div><label class="m-t-xs">&nbsp;</label></div>
        <a href="projects/create" class="btn btn-primary"><i class="fas fa-plus"></i>
            {{ __('add_project') }}</a>
      </div>
    </div>
    

    <div v-dragscroll:nochilddrag class="agile-board pt-1">
      <div
        class="col-agile mx-1"
        :style="{ 'border-top': '3px solid' + status.colour }"
        v-for="status in projectStatuses"
        :key="status.id"
        data-dragscroll
      >
        <div class="ibox" data-dragscroll>
          <div class="ibox-content">
            <h3>{{ statusesTranslaction[status.id] }}</h3>
            <draggable
              :scroll-sensitivity="100"
              :scroll-speed="20"
              :force-fallback="true"
              class="sortable-list connectList agile-list agile-board-column"
              group="projects"
              :list="status.projects"
              @change="log($event, status)"
            >
              <li
                v-for="project in status.projects"
                :key="project.id"
                :style="{ 'border-left': '3px solid' + status.colour }"
              >
                <a :href="goToRouteShow(project.id)" style="color: black">
                  <i
                    v-if="project.priority"
                    class="fas fa-exclamation-triangle text-danger"
                  ></i>
                  <span
                    class="label label-danger"
                    v-if="project.unreadCommentsCount > 0 && project.access"
                    >{{ project.unreadCommentsCount }}</span
                  >
                  <span v-if="project.unreadCommentsCount > 0">&nbsp;</span>
                  <strong>{{ project.name }}</strong>
                  <div class="agile-detail overflow-hidden">
                    {{ project.client.name }} | Utworzono:
                    {{ project.created }}
                    <div class="d-block">
                      <avatar
                        v-for="manager in project.managers"
                        :key="project.id + 'manager' + manager.id"
                        class="
                          avatar-in-board
                          float-left
                          rounded-circle
                          tooltip-more
                          mr-1
                          mt-1
                        "
                        :username="manager.name"
                        :initials="initials(manager.name)"
                        :src="asset(manager.avatar)"
                        :size="30"
                        :title="manager.name"
                        backgroundColor="gray"
                        color="white"
                      >
                      </avatar>
                      <avatar
                        v-for="member in project.team_members"
                        :key="project.id + member.id"
                        class="
                          avatar-in-board
                          float-left
                          rounded-circle
                          tooltip-more
                          mr-1
                          mt-1
                        "
                        :username="member.name"
                        :initials="initials(member.name)"
                        :src="asset(member.avatar)"
                        :size="30"
                        :title="member.name"
                        backgroundColor="gray"
                        color="white"
                      >
                      </avatar>
                                            <avatar
                        :key="133"
                        class="
                          avatar-in-board
                          float-left
                          rounded-circle
                          tooltip-more
                          mr-1
                          mt-1
                        "
                        :src="'storage/images/avatars/jf30iIx3pYzDxdIKVmgPYh0yuJSfMyK1Dh3Eva0tVFJ7wilaz8YGxL0le9W2pccgOL7Ra5nCLlfrCevnpIzs8GGhApGBixHdiZ5C.png'"
                        :size="30"
                        :title="'ww'"
                        backgroundColor="gray"
                        color="white"
                      >
                      </avatar>
                    </div>
                  </div>
                </a>
              </li>
            </draggable>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import draggable from "vuedraggable";
import { dragscroll } from "vue-dragscroll";
import Avatar from "vue-avatar";
import Multiselect from "vue-multiselect";
export default {
  directives: {
    dragscroll,
  },
  name: "projects-board",
  display: "Projects Board",
  order: 1,
  components: {
    draggable,
    Avatar,
    Multiselect,
  },
  props: {
    statusesTranslaction : { defult: [] },
    propProjectStatuses: { type: Array, default: [] },
    routeShow: { defult: "" },
    projectStatusesInFilter: { type: Array, defult: [] },
    projects: { type: Array, defult: [] },
    clients: { type: Array, defult: [] },
    users: { type: Array, defult: [] },
    sessionFilters: { defult: [] },
  },
  data() {
    return {
      priorities: [],
      projectStatuses: [],
      projectStatusFilter: [],
      projectFilter: [],
      clientFilter: [],
      userFilter: [],
      priorityFilter: [],
    };
  },
  mounted() {
    this.priorities = [
        { value: 1, name: this.__("yes") },
        { value: 0, name: this.__("no") }
      ];
    this.prepareStatusFilteres();
    this.prepareClientFilter();
    this.preparePriorityFilter();
    this.prepareProjectFilter();
    this.prepareUserFilter();
    this.projectStatuses = this.propProjectStatuses;
  },
  methods: {
    prepareClientFilter() {
      var self = this;
      this.sessionFilters.clientFilter.forEach(function(v) {
        var val = self.clients.find(option => option.name === v);
        self.clientFilter.push(val)
      }); 
    },
    preparePriorityFilter() {
      var self = this;
      this.sessionFilters.priorityFilter.forEach(function(v) {
        if(v == 1) {
          self.priorityFilter.push({ value: 1, name: "Tak" })
        }
        if(v == 0) {
          self.priorityFilter.push({ value: 0, name: "Nie" })
        }
      }); 
    },
    prepareProjectFilter() {
      var self = this;
      this.sessionFilters.projectFilter.forEach(function(v) {
        var val = self.projects.find(option => option.name === v);
        self.projectFilter.push(val)
      }); 
    },
    prepareUserFilter() {
      var self = this;
      this.sessionFilters.userFilter.forEach(function(v) {
        var val = self.users.find(option => option.name === v);
        self.userFilter.push(val)
      });
    },
    prepareStatusFilteres() {
      var self = this;
      this.sessionFilters.projectStatusFilter.forEach(function(v) {
        if(v !== null) {
          var val = self.projectStatusesInFilter.find(option => option.model_id === v);
          self.projectStatusFilter.push(val)
        }
      });
    },
    filterProjects() {
      let projectStatusFilterPrepared = this.pluckModelIds(this.projectStatusFilter);
      let projectFilterPrepared = this.pluck(this.projectFilter);
      let clientFilterPrepared = this.pluck(this.clientFilter);
      let userFilterPrepared = this.pluck(this.userFilter);
      let priorityFilterPrepared = this.priorityFilter.map((o) => o.value);
      axios
        .post("/api/project-board/filter", {
          projectStatusFilter: projectStatusFilterPrepared,
          projectFilter: projectFilterPrepared,
          clientFilter: clientFilterPrepared,
          userFilter: userFilterPrepared,
          priorityFilter: priorityFilterPrepared,
        })
        .then((response) => {
          this.projectStatuses = response.data.projectStatuses;
        })
        .catch((error) => {});
    },
    pluck(array) {
      return array.map((o) => o.name);
    },
    pluckModelIds(array) {
      return array.map((o) => o.model_id);
    },
    addTag(newTag, filter, filters) {
      const tag = {
        name: newTag,
        code: newTag.substring(0, 2) + Math.floor(Math.random() * 10000000),
      };
      filters.push(tag);
      filter.push(tag);
      this.filterProjects();
    },
    initials(name) {
      return name.substring(0, 2).toUpperCase();
    },
    asset(path) {
      let imagePath = null;
      if (path != null) {
        let base_path = window._asset || "";
        imagePath = base_path + path;
      }
      return imagePath;
    },
    log: function (evt, status) {
      let projectStatusId = status.id;
      let order = this.projectStatuses.find(
        (obj) => obj.id === projectStatusId
      ).projects;
      if (evt.added != undefined) {
        let added = evt.added.element.id;

        axios
          .post("/api/project-board/change-project-status", {
            projectStatusId: projectStatusId,
            projectId: added,
          })
          .then((response) => {})
          .catch((error) => {});
      }
      this.changeOrder(order);
      document.querySelector(".agile-board").click();
    },
    changeOrder(order) {
      axios
        .post("/api/project-board/change-order", {
          projectsInOrder: order,
        })
        .then((response) => {})
        .catch((error) => {});
    },
    goToRouteShow: function (id) {
      return `${this.routeShow}/${id}`;
    },
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style>
.multiselect {
  font-weight: 400;
  line-height: 1.5;
  font-family: "open sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 13px;
  color: #676a6c;
}
.multiselect__tags {
  border-radius: 0px;
  font-size: 13px;
  min-height: 36px;
  padding-top: 6px;
}
.multiselect__tag {
  background: #e4e4e4;
  color: #676a6c;
  border: 1px solid #aaa;
  border-radius: 4px;
}

.multiselect__tag-icon {
  color: #676a6c !important;
}

.multiselect__tag-icon:hover {
  background: #e4e4e4;
  color: #333 !important;
}

.multiselect__tag-icon::after {
  color: #676a6c !important;
}

.multiselect__placeholder {
  margin-bottom: 0px;
  color: #7f7f7f;
}

.multiselect__option {
  padding: 6px;
}

.multiselect__element {
  max-height: 31.5px;
}

.multiselect__select {
  top: -2px;
}

.multiselect__content-wrapper {
  border-bottom-left-radius: 0px;
  border-bottom-right-radius: 0px;
}

.multiselect__option {
  min-height: 31.5px;
}
.multiselect__option--highlight,
.multiselect__option--highlight.multiselect__option--selected {
  background: #5897fb;
  min-height: 31.5px;
  max-height: 31.5px;
}

.multiselect__option--highlight:after {
  background: #5897fb;
}

.multiselect_option.multiselect__option--selected {
  background: #dddddd;
  max-height: 31.5px;
}

.multiselect__option--selected {
  font-weight: 400;
}
</style>