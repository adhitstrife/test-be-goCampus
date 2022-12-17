<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Article
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 shadow-md">
                    <button onclick="openModal()" id="open-modal" class="p-2 bg-blue-600 text-white">Create</button>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full">
                          <thead>
                              <tr>
                                  <th class="px-6 py-3 text-left text-gray-500 border-b border-gray-200 bg-gray-50">
                                      Id
                                  </th>
                                  <th class="px-6 py-3 text-left text-gray-500 border-b border-gray-200 bg-gray-50">
                                      Title
                                  </th>
                                  <th class="px-6 py-3 text-left text-gray-500 border-b border-gray-200 bg-gray-50">
                                    Content
                                  </th>
                                  <th class="px-6 py-3 text-left text-gray-500 border-b border-gray-200 bg-gray-50">
                                    Image
                                  </th>
                                  <th class="px-6 py-3 text-left text-gray-500 border-b border-gray-200 bg-gray-50">
                                    Creator
                                  </th>
                                  <th class="px-6 py-3 text-left text-gray-500 border-b border-gray-200 bg-gray-50">
                                      Edit
                                  </th>
                                  <th class="px-6 py-3 text-left text-gray-500 border-b border-gray-200 bg-gray-50">
                                      Delete
                                  </th>
                              </tr>
                          </thead>
  
                          <tbody class="bg-white">
                              @foreach($articles as $article)
                              <tr class="hover:bg-blue-100">
                                  <td class="px-6 py-4 border-b border-gray-200">
                                      <div class="flex items-center">
                                          <div class="ml-4">
                                              <div class="text-sm text-gray-900">
                                                  {{ $article->id }}
                                              </div>
                                          </div>
                                      </div>
                                  </td>
  
                                  <td onclick="openModalDetail({{ $article }})" class="px-6 py-4 border-b border-gray-200 cursor-pointer">
                                      <div class="text-sm text-gray-900">
                                          {{ $article->title }}
                                      </div>
                                  </td>
                                  
                                  <td class="px-6 py-4 border-b border-gray-200">
                                    <div class="text-sm text-gray-900">
                                        {{ $article->content }}
                                    </div>
                                  </td>

                                  <td class="px-6 py-4 border-b border-gray-200">
                                    <div class="text-sm text-gray-900">
                                        <a href="{{ asset('storage/article_images/'.$article->article_image) }}" target="_blank">
                                            <img class="w-10 h-10" src="{{ asset('storage/article_images/'.$article->article_image) }}" alt="">
                                        </a>
                                    </div>
                                  </td>

                                  <td class="px-6 py-4 border-b border-gray-200">
                                    <div class="text-sm text-gray-900">
                                        {{ $article->user->name }}
                                    </div>
                                  </td>
                                
                                  <td class="px-6 py-4 border-b border-gray-200">
                                      <button onclick="openModalEdit({{ $article }})" class="px-4 py-2 text-white bg-blue-600">
                                          Edit
                                      </button>
                                  </td>
  
                                  <td class="px-6 py-4 text-sm text-gray-500 border-b border-gray-200">
                                      <button onclick="deleteArticle({{ $article->id }})"
                                          class="px-4 py-2 text-white bg-red-600">
                                          Delete
                                      </button>
                                  </td>
  
                              </tr>
                              @endforeach
                            </tbody>
                      </table>
                      {{ $articles->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="fixed hidden inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="my-modal">
        <!--modal content-->
        <div
        class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white"
        >
            <div class="mt-3 text-center">
                <div class="container mx-auto">
                    <div id="errorContainer" class="error-message bg-red-400 text-white p-2 mb-4 hidden">
                        <p id="error"></p>
                    </div>
                    <input type="hidden" name="" id="urlSubmit">
                    <form id="form" method="POST" onsubmit="event.preventDefault()">
                        @csrf
                        <div>
                            <label for="title">Title</label>
                            <input id="title" type="text" class="w-full py-2 rounded">
                            @error('title')
                            <span class="text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-8">
                            <label class="block mb-2 text-xl">Content </label>
                            <textarea  id="content" rows="3" cols="20" class="w-full rounded"></textarea>
                            @error('description')
                            <span class="text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-8">
                            <img class="h-20 w-20" id="currentImage" src="" alt="">
                        </div>
                        <div class="mt-8">
                            <input id="image" type="file" class="w-full py-2 rounded" required>
                            @error('articleImage')
                            <span class="text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                        <button onclick="submitArticle()" class="px-4 py-2 mt-4 text-white bg-blue-600 rounded">
                            Submit
                        </button>
                        <button onclick="closeModal()" id="close-modal" class="px-4 py-2 text-white bg-red-600 rounded">
                            Cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed hidden inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="detail-modal">
        <!--modal content-->
        <div
        class="relative top-20 mx-auto p-5 border w-1/2 shadow-lg rounded-md bg-white"
        >
            <div class="close-button flex justify-end">
                <button onclick="closeDetailModal()" class="cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>                              
                </button>
            </div>
            <div class="mt-3">
                <div class="container mx-auto">
                    <div class="title border-b py-4">
                        <p class="text-4xl font-bold" id="detail-title">
                        </p>
                    </div>
                    <div class="content flex py-8">
                        <div class="image">
                            <img src="" alt="" srcset="" class="h-40 w-40" id="detail-image">
                        </div>
                        <div class="content pl-8">
                            <p id="detail-text"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.2.min.js" integrity="sha256-2krYZKh//PcchRtd+H+VyyQoZ/e3EcrkxhM8ycwASPA=" crossorigin="anonymous"></script>

    <x-slot name="script">
        <script>
            function openModal() {
                let errorElement = document.getElementById("errorContainer");
                errorElement.style.display = "none"

                $('#image').val(null)
                $('#title').val(null)
                $('#content').val(null)
                $("#currentImage").attr('src', "")

                let modal = document.getElementById("my-modal");
                let url = document.getElementById("urlSubmit");
                
                url.value = "{{  route('article.store') }}"
                modal.style.display = "block"
            }

            function openModalDetail(article) {
                let modal = document.getElementById("detail-modal");
                modal.style.display = "block"

                $("#detail-title").text(article.title)
                $("#detail-text").text(article.content)
                $("#detail-image").attr('src', "{{ asset('storage/article_images/') }}"+ "/" + article.article_image)
            }

            function closeDetailModal() {
                $("#detail-title").text("")
                $("#detail-text").text("")
                $("#detail-image").attr('src', "")

                let modal = document.getElementById("detail-modal");
                modal.style.display = "none"
            }

            function closeModal() {
                let modal = document.getElementById("my-modal");
                modal.style.display = "none"
            }

            function submitArticle() {
                let title = document.getElementById("title").value
                let content = document.getElementById("content").value
                let image = document.getElementById("image").files
                let submitUrl = document.getElementById("urlSubmit").value

                var formData = new FormData()
                formData.append('title', title)
                formData.append('content', content)
                formData.append('image', image[0])
                
                if ($("#currentImage").attr('src') !== undefined) {
                    $("#image").prop('required',false)
                }
                

                if (image[0] !== undefined || $("#currentImage").attr('src') !== "") {
                    $.ajax({
                        type: "POST",
                        url: submitUrl,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        mimeType: "multipart/form-data",
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        encode: true
                    }).then((response) => {
                        if(response.status == 200) {
                            location.reload()
                        }
                    }).catch((e) => {
                        if (e.responseJSON.message) {
                            let error = $("#error")
                            error.text(e.responseJSON.message)
                            let errorElement = document.getElementById("errorContainer");
                            errorElement.style.display = "block"
                        }
                    })
                } else {
                    let error = $("#error")
                    error.text("image is required")
                    let errorElement = document.getElementById("errorContainer");
                    errorElement.style.display = "block"
                }
                
            }

            function openModalEdit(article) {
                let errorElement = document.getElementById("errorContainer");
                errorElement.style.display = "none"

                $('#image').val(null)

                let title = document.getElementById("title").value = article.title
                let content = document.getElementById("content").value = article.content

                let url = document.getElementById("urlSubmit");
                stockUrl = "{{  route('article.update', ":article") }}"
                updatedUrl = stockUrl.replace(':article', article.id)
                url.value = updatedUrl

                $("#currentImage").attr('src', "{{ asset('storage/article_images/') }}"+ "/" + article.article_image)
                let modal = document.getElementById("my-modal");
                modal.style.display = "block"
            }

            function deleteArticle(id) {

                stockUrl = "{{  route('article.destroy', ":id") }}"
                updatedUrl = stockUrl.replace(':id', id)
                $.ajax({
                        type: "DELETE",
                        url:  updatedUrl,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        encode: true
                    }).then((response) => {
                        if(response.status == 200) {
                            location.reload()
                        }
                    }).catch((e) => {
                        if (e.responseJSON.message) {
                            let error = $("#error")
                            error.text(e.responseJSON.message)
                            let errorElement = document.getElementById("errorContainer");
                            errorElement.style.display = "block"
                        }
                    })
            }
        </script>
    </x-slot>
</x-app-layout>