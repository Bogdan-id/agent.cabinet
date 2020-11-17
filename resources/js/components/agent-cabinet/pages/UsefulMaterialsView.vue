<template>
    <div>
      <bread-scrumb />  
      <div class="col-md-12 useful-materials pt-0 pl-0 pr-0">
        <keep-alive>
          <useful-material-categories v-show="$route.name === 'Кориснi матерiали'">
          </useful-material-categories>
        </keep-alive>
        <router-view></router-view>
      </div>
    </div>
</template>
<script>
import BreadScrumb from '../components/breadScrumb'
import UsefulMaterialCategories from './UsefulMaterialCategories'

export default {
  components: {
    BreadScrumb,
    UsefulMaterialCategories
  },
}
</script>
