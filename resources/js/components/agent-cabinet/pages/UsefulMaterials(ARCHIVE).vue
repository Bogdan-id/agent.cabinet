<template>
    <div class="row">
      <div class="col-md-12 useful-materials">
        <v-card>
          <v-card-title class="d-block">
            <div>
              Кориснi матерiали
            </div>
          <v-divider></v-divider>
          </v-card-title>
          <v-card-text>
            <div 
              v-for="(item, key) in articles"
              :key="key"
              class="useful-materials__article">
              <div class="headline"><a href="#">{{ item.title }}</a></div>
              <span class="article-title">{{ item.date }}</span>
              <p>{{ item.text }}<a :href="item.link"></a></p>
            </div>
          </v-card-text>
        </v-card>
      </div>
    </div>
</template>
<script>
export default {
  data: () => ({
    articles: [
      {
        date: 'Березень 2019',
        title: "Iнформацiя про нас",
        link: '#',
        text: `Компанія Best Leasing завжди пропонує для своїх Партнерів та 
          Клієнтів найкращі умови лізингу! А ще найкращий сервіс, індивідуальний підхід, 
          спеціальні пропозиції, розіграші, професійну підтримку від найкращої команди 
          професіоналів, програма лояльності та ще купа додаткових бонусів.`
      },
      {
        date: 'Квiтень 2019',
        title: "Тренінги",
        link: '#',
        text: `Компанія Best Leasing завжди пропонує для своїх Партнерів та 
          Клієнтів найкращі умови лізингу! А ще найкращий сервіс, індивідуальний підхід, 
          спеціальні пропозиції, розіграші, професійну підтримку від найкращої команди 
          професіоналів, програма лояльності та ще купа додаткових бонусів. Генеатор тексту`
      }
    ]
  }),
}
</script>
<style lang="scss">
  .useful-materials {
    p {
      display: none;
      color: black;
    }
    .useful-materials__article {
      padding-bottom: 15px;
      p:first-of-type {
        display: inline-block;
        & ::after {
          content: "читати далi...";
          color: #d32f2f;
          margin-left: 8px;
        }
      }
      & .article-title {
        display: block;
        margin-bottom: 5px;
        font-size: 15px;
        color: #666666;
      }
      .headline {
        & a {
          color: #d32f2f
        }
      }
    }
  }
  
</style>
