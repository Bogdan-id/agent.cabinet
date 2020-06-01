<template>
  <v-card>
    <v-card-title class="d-block grey darken-3 white--text">
      <v-icon class="mb-2 mr-3" color="grey lighten-2" v-text="'mdi-bookmark'"></v-icon>
      Повiдомлення
    <v-divider></v-divider>
    <v-progress-linear
      :height="3"
      :active="loading"
      :indeterminate="loading"
      absolute
      top
      color="red lighten-1">
    </v-progress-linear>
    </v-card-title>
    <v-card-text>
      <section class="section" id="section">
        <div class="tabs" style="display: inline-flex;">
          <div class="tabs-input active" style="width: auto">
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
          <div class="tabs-input" style="width: auto">
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
          <div class="tabs-input" style="width: auto">
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
                  Новини компанії 
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
      let tabs = document.querySelectorAll('#section .tabs-input')
      tabs.forEach(element => element.classList.remove('active'))
      event.target.parentNode.classList.add('active')
    },
  }
}
</script>

<style scoped>
  .tabs {
    border-bottom: 2px solid #ea4c45;
  }
  .tabs .tabs-input {
    opacity: 1;
  }
  .tabs .tabs-input label {
    transition: background;
    font-size: 1rem; 
    margin-bottom: auto; 
    padding: 6px 10px;
  }
  .tabs .tabs-input.active label {
    background: #ea4c45!important;
    color: white!important;
  }
</style>