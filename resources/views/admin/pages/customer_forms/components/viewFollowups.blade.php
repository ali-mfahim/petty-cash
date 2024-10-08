<section class="basic-timeline">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Followup History</h4>
                </div>
                <div class="card-body" style="max-height: 600px !important;overflow-y: scroll;">
                    <ul class="timeline">
                        @if(isset($followups) && !empty($followups) && count($followups) > 0)
                        @foreach($followups as $index => $value)
                        <li class="timeline-item">
                            <span class="timeline-point timeline-point-indicator"></span>
                            <div class="timeline-event">
                                <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                    <h6>{{getUserName($value->user) ?? '-'}}</h6>
                                    <span class="timeline-event-time" style="font-weight: bold;">{{formatDateTime($value->created_at)}} / {{$value->created_at->diffForHumans()}}</span>
                                </div>
                                <span class="badge bg-danger">{{$value->title ?? ''}} </span> <span class="badge bg-success">{{ getFormStatus($value)->name ?? '' }}</span>
                                <p>{{$value->remarks ?? '-'}}</p>
                                <!-- <div class="d-flex flex-row align-items-center">
                                    <img class="me-1" src="../../../app-assets/images/icons/file-icons/pdf.png" alt="invoice" height="23" />
                                    <span>invoice.pdf</span>
                                </div> -->
                            </div>
                        </li>
                        @endforeach
                        @endif


                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>