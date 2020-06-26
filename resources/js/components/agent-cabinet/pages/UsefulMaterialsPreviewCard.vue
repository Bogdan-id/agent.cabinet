<template>
  <div>
    <v-hover 
      v-slot:default="{ hover }">
      <v-card
        @click="toMaterial()"
        :elevation="hover ? 4 : 1"
        class="d-flex ml-3 mr-3 mb-5">
        <v-row class="ml-1 mr-1">
          <v-col xs="12" sm="4" md="2">
            <div class="image-column d-flex justify-center">
              <v-img
                v-if="item.image"
                :src="item.image"
                width="365">
              </v-img>
              <svg-gallery-icon 
                v-if="!item.img"
                :width="'100%'"
                :height="'auto'"
                style="max-width: 180px!important; min-width: 110px">
              </svg-gallery-icon>
            </div>
          </v-col>
          <v-col xs="12" sm="8" md="10" style="display: flex; align-items: center;">
            <div class="text-column pa-3 pt-0 pb-0">
              <div>
                <span class="mt-0 mb-1 presentation-card-paragraph" style="font-size: 1.1rem; line-height: 1.9rem;">{{ item.title}}</span>
              </div>
              <div class="text" v-html="item.content" style="position: relaitve">
              </div>
              <span style="letter-spacing: 0.065rem; font-size: 0.78rem; color: black; line-height: 2rem;">
                12.06.2014 26.10
              </span>
            </div>
          </v-col>
        </v-row>
      </v-card>
    </v-hover>
  </div>
</template>

<script>
import svgGalleryIcon from '../assets/svg-icons/gallery'

export default {
  components: {
    svgGalleryIcon
  },
  props: ['item', 'route'],
  data: () => ({
    routeItem: null,
  }),
  methods: {
    toMaterial() {
      this.$router.push({name: 'Детально', params: {id: this.item.id}})
    }
  },
  created() {
    // console.log('***************')
    // console.log(this.$router.currentRoute)
    // console.log('***************')
  }
}
</script>

<style scoped>
.text >>> * {
  display: none;
}
.text >>> *:first-child {
  padding: 0;
  margin: 0;
  max-height: 130px;
  max-width: 100%;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
}
/* .text >>> *:first-child:after {
  content: 'sdflkj'
} */
</style>

<style lang="scss">
.text {
   overflow: hidden;
   text-overflow: ellipsis;
   display: -webkit-box;
   -webkit-line-clamp: 5; /* number of lines to show */
   -webkit-box-orient: vertical;
}
.presentation-card-paragraph {
  p {
    font-size: 0.85rem!important;
  }
}
.usefull-material__detail {
  width: 100%;
  // p {
  //   font-size: 16px;
  //   display: block;
  // }
  // .image-column {
  //   width: 100%;
  // }
  // .text-column {
  //   width: 100%;
  //   display: flex;
  //   flex-direction: column;
  // }
}
</style>