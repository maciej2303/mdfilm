<template>
  <div class="ibox">
    <div class="ibox-content">
      <div class="row">
        <div class="col-12">
          <button
            type="button"
            class="btn btn-block btn-primary movie-accept"
            v-if="versionAvaliableAcceptance == true"
            @click="showAcceptanceModal()"
          >
            <i class="fas fa-check"></i> {{ __('accept') }}
          </button>
        </div>
      </div>
      <div class="row">
        <div
          class="col-lg-3"
          v-for="acceptance in versionAcceptances"
          :key="acceptance.id"
        >
          <div class="widget style2 navy-bg" v-if="acceptance.acceptance === 1">
            <div class="row">
              <div class="col-3">
                <i class="fas fa-user-check fa-3x"></i>
              </div>
              <div class="col-9 text-right">
                <span class="font-bold">{{ acceptance.user.name }}</span
                ><br />
                <small
                  >{{ __('accepted') }}
                  {{ acceptance.acceptance_date | formatDate }}</small
                >
              </div>
            </div>
          </div>
          <div class="widget style2 yellow-bg" v-else>
            <div class="row">
              <div class="col-3">
                <i class="fas fa-user-clock fa-3x"></i>
              </div>
              <div class="col-9 text-right">
                <span class="font-bold">{{ acceptance.user.name }}</span>
                <br />
                <small>{{ __('pending_for_accept') }}</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
Vue.use(VueSweetalert2);

export default {
  name: "AcceptanceBox",
  props: [
    "acceptances",
    "initialUser",
    "avaliableAcceptance",
    "projectVersion",
  ],
  data() {
    return {
      versionAcceptances: this.acceptances,
      loggedUser: this.initialUser,
      versionAvaliableAcceptance: this.avaliableAcceptance,
      version: this.projectVersion,
    };
  },
  methods: {
    showAcceptanceModal() {
      this.$swal({
        title: this.__('accept_?') ,
        text: this.__('do_you_really_want_to_accpet_version_acceptation_wont_be_able_to_return'),
        type: "warning",
        showCancelButton: true,
        cancelButtonText: this.__('no'),
        closeOnConfirm: true,
        closeOnCancel: true,
        confirmButtonColor: "#1ab394",
        confirmButtonText: this.__('yes'),
        icon: "warning",
        reverseButtons: true,
      }).then((result) => {
        if (result.value) {
          this.acceptVersion();
        }
      });
    },
    acceptVersion() {
      axios
        .post("/api/project-versions/version-acceptance", {
          user_id: this.loggedUser.id,
          project_element_component_version_id: this.version.id,
        })
        .then((response) => {
          if (response.data.refresh != undefined) {
            window.location.reload();
          } else {
            this.versionAcceptances = response.data.acceptances;
            this.versionAvaliableAcceptance = response.data.avaliableAcceptance;
          }
        });
    },
  },
};
</script>
