<script id="apt-template" type="text/x-handlebars-template">

  <div class="col-md-6">
    <div class="card my-2 show-apt-link rounded shadow bg-white p-2" style="max-width: 540px;" data-id="{{id}}">
        <div class="row no-gutters">
          <div class="col-lg-4 p-1">
          <div class="image-faker rounded" style="background-image: url('{{ img }}')"></div>
            <!-- <img src="{{ img }}" class="card-img" alt="..."> -->
          </div>
          <div class="col-lg-8">
            <div class="card-body p-2 h-100 d-flex flex-column justify-content-around">
              <h5 class="card-title text-capitalize mb-1">{{ title }}</h5>
              <p class="card-text text-capitalize font-italic mb-1">{{ address }}</p>
              <ul class="list-inline mb-0">
                        <li class="list-inline-item"> <i class="pr-2 fas fa-bed"></i>{{ bed_qt }} </li>
                        <li class="list-inline-item"> <i class="px-2 fas fa-shower"></i>{{ bathroom_qt }} </li>
                        <li class="list-inline-item"> <i class="px-2 fas fa-ruler-combined"></i>{{ mq }} sm </li>
                        <li class="list-inline-item"> <i class="px-2 fas fa-couch"></i>{{ room_qt }} </li>
                    </ul>
              <label class="mb-0 mt-1"><strong>Services</strong></label>
              <ul class="list-inline mb-0 service-icons-list">

              </ul>
            </div>
          </div>
        </div>
    </div>
  </div>

</script>