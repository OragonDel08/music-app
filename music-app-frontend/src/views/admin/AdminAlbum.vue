<script setup lang="ts">
  import { onMounted, ref, watch, computed } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import { useAuthStore } from '@/stores/auth';
  import { axiosApi } from '@/lib/axios'
  import  HandThumbDownIcon from '@/icons/HandThumbDownIcon.vue'
  import  HandThumbUpIcon from '@/icons/HandThumbUpIcon.vue'
  import  TrashIcon from '@/icons/TrashIcon.vue'
  import  CheckIcon from '@/icons/CheckIcon.vue'
  import  CloseIcon from '@/icons/CloseIcon.vue'
  import MagnifyingGlassIcon from '@/icons/MagnifyingGlassIcon.vue'

  const route = useRoute()
  const router = useRouter()
  const authStore = useAuthStore()

  // Reactive state
  const albums = ref<any>({
    data: [],
    current_page: 1,
    last_page: 1,
  })

  const deleteItem = ref<any>(null)
  const isDeleting = ref<boolean>(false)
  const isVoting = ref<boolean>(false)
  const isLoading = ref<boolean>(false)

  // Computed: Get current search and page from URL query
  const searchQuery = computed(() => (route.query.search as string) || '')
  const currentPage = computed(() => parseInt(route.query.page as string) || 1)

  // Fetch data from backend
  const fetchAlbums = async () => {
    isLoading.value = true
    try {
      const res = await axiosApi.get('/api/albums', {
        params: {
          search: searchQuery.value,
          page: currentPage.value,
        },
      })
      albums.value = res.data
    } catch (err) {
      console.error('Error fetching albums:', err)
    }
    isLoading.value = false
  }

  // Update search query in URL
  const onSearchInput = (event: Event) => {
    const value = (event.target as HTMLInputElement).value
    router.replace({ query: { ...route.query, search: value, page: '1' } })
  }

  // Go to a specific page
  const goToPage = (page: number) => {
    router.replace({ query: { ...route.query, page: page.toString() } })
  }

  const deleteAlbum = (item: any) => {
    deleteItem.value = item
  }

  const confirmDelete = async () => {
    isDeleting.value = true
    try {
      const res = await axiosApi.delete(`/api/albums/${deleteItem.value.id}`);
      console.log(res)
      fetchAlbums()
    } catch (err) {
      console.error('Error fetching albums:', err)
    }
    deleteItem.value = null
    isDeleting.value = false
  }

  const cancelDelete = () => {
    deleteItem.value = null
  }

  const upvote = async (item: any) => {
    isVoting.value = true
    try {
      const res = await axiosApi.post(`/api/albums/${item.id}/vote`,{
        vote_type: 'upvote'
      });
      console.log(res)
      fetchAlbums()
    } catch (err) {
      console.error('Error fetching albums:', err)
    }
    isVoting.value = false
  }

  const downvote = async (item: any) => {
    isVoting.value = true
    try {
      const res = await axiosApi.post(`/api/albums/${item.id}/vote`,{
        vote_type: 'downvote'
      });
      console.log(res)
      fetchAlbums()
    } catch (err) {
      console.error('Error fetching albums:', err)
    }
    isVoting.value = false
  }

  // Re-fetch data on route query change
  watch(
    () => route.query,
    () => {
      fetchAlbums()
    },
    { immediate: true }
  )
</script>
<template>
  <div class="album-container">
    <div class="album-header">
      <h1>Album List</h1>
      <div>
        <input
          type="text"
          :value="searchQuery"
          @input="onSearchInput"
          placeholder="Search albums..."
        />
        <MagnifyingGlassIcon class="search-icon" />
      </div>
    </div>
    
    <div v-if="!isLoading" class="album-content">
      <div v-for="album in albums.data" class="card">
        <img class="card__image" src="/src/assets/music-default.png" alt="Card Image">
        <div class="card__overlay">
          <div style="padding: 1rem;">
            <h3>{{ album.title }}</h3>
            <p>{{ album.artist }}</p>
            <div class="card__actions">
              <div class="icon-btn-group">
                <button class="icon-btn" @click="upvote(album)" :disabled="isVoting">
                  <HandThumbUpIcon :class="['icon', { active: album.votes.some((vote: any) => (vote.user_id === authStore.user.id && vote.vote_type === 'upvote')) }]"/>
                  <span>{{ album.upvote_count }}</span>
                </button>
                <button class="icon-btn" @click="downvote(album)" :disabled="isVoting">
                  <HandThumbDownIcon :class="['icon', { active: album.votes.some((vote: any) => (vote.user_id === authStore.user.id && vote.vote_type === 'downvote')) }]"/>
                  <span>{{ album.downvote_count }}</span>
                </button>
              </div>
              <button class="icon-btn-trash" @click="deleteAlbum(album)">
                <TrashIcon class="icon"/>
              </button>
            </div>
          </div>
        </div>
        <div v-if="deleteItem?.id === album.id" class="delete-confirmation">
          <span>Are you sure to delete?</span>
          <div class="icon-btn-group">
            <button class="icon-btn" @click="confirmDelete()" :disabled="isDeleting">
              <CheckIcon class="icon"/>
            </button>
            <button class="icon-btn" @click="cancelDelete()" :disabled="isDeleting">
              <CloseIcon class="icon"/>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div v-if="isLoading" class="page-spinner">
        <div class="spinner"></div>
    </div>

    <div class="album-footer">
      <div class="pagination">
        <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1">Previous</button>
        <span>Page {{ currentPage }} of {{ albums.last_page }}</span>
        <button @click="goToPage(currentPage + 1)" :disabled="currentPage === albums.last_page">Next</button>
      </div>
    </div>
    
  </div>
  
</template>