<template>
  <v-card elevation="8">
    <v-card-title class="d-block grey darken-3 white--text">
      <v-icon class="mb-2 mr-3" color="grey lighten-2" v-text="'mdi-bookmark'"></v-icon>
      Повiдомлення
    <v-progress-linear
      :height="3"
      :active="loading"
      :indeterminate="loading"
      absolute
      top
      color="red lighten-1">
    </v-progress-linear>
    </v-card-title>
    <v-card-text style="min-height: 100vh">
      <section class="section" style="margin-top: 10px;" id="section">
        <div class="notification-tabs" style="display: inline-flex;">
          <div class="notification-tabs-input active" style="width: auto">
            <label
              @click.stop="changeActive($event)" 
              for="tab-1"
              class="label">
              Інформація по проектах
            </label>
            <input 
              @click.stop="changeActive($event)"
              style="border-bottom: 0"
              id="tab-1" 
              name="tab" 
              type="radio" 
              value="1" 
              checked="checked" 
              v-model="currentTab"/>
            <div class="content">
              <v-card-text>
                <v-card-title class="edit-title pb-6">Інформація по проектах</v-card-title>
              </v-card-text>
            </div>
          </div>
          <div class="notification-tabs-input" style="width: auto">
            <label 
              @click.stop="changeActive($event)" 
              for="tab-2" 
              class="label">
              Акції та умови лізингу
            </label>
            <input
              @click.stop="changeActive($event)"
              style="border-bottom: 0"
              id="tab-2" 
              name="tab" 
              type="radio" 
              value="2" 
              v-model="currentTab">
            <div class="content">
              <v-card-text>
                <v-card-title class="edit-title pb-6">
                  Акції та умови лізингу
                </v-card-title>
              </v-card-text>
            </div>
          </div>
          <div class="notification-tabs-input" style="width: auto">
            <label 
              @click.stop="changeActive($event)"
              for="tab-3" 
              class="label">
              Новини компанії
            </label>
            <input
              @click.stop="changeActive($event)"
              style="border-bottom: 0"
              id="tab-3" 
              name="tab" 
              type="radio" 
              value="3" 
              v-model="currentTab">
            <div class="content">
              <v-card-text>
                <v-card-title class="edit-title pb-6">
                  Акції та умови лізингу
                </v-card-title>
              </v-card-text>
            </div>
          </div>
        </div>
        <!-- <v-btn @click="test()">test</v-btn> -->
      </section>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  data: () => ({
    loading: false,
    currentTab: '1',
  }),
  methods: {
    changeActive(event) {
      console.log(event)
      let tabs = document.querySelectorAll('#section .notification-tabs-input')
      tabs.forEach(element => element.classList.remove('active'))
      event.target.parentNode.classList.add('active')
    },
  }
}
</script>

<style scoped lang="scss">
  .notification-tabs {
    border-bottom: 2px solid #d85151;
  }
  .notification-tabs .notification-tabs-input {
    opacity: 1;
    input {
      display: none;
    }
    .content {
      position: absolute;
      left: 100%;
      opacity: 0;
      transition: left 0.5s, opacity 0.2s;
    }
    &.active {
      .content {
        left: 0;
        opacity: 1;
      }
    }
  }
  .notification-tabs .notification-tabs-input label {
    transition: background 0.25s ease-in, color 0.25s ease-in;
    font-size: 1rem; 
    margin-bottom: auto; 
    padding: 6px 10px;
  }
  .notification-tabs .notification-tabs-input.active label {
    background: #d85151!important;
    color: white!important;
  }
</style>