@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-robot me-2"></i>AI Assistant
                    </h4>
                    <p class="mb-0 small">Get personalized guidance and support</p>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="fas fa-robot fa-3x text-primary"></i>
                        </div>
                        <h5>Welcome to your AI Assistant</h5>
                        <p class="text-muted">I'm here to help you with parenting guidance, activity recommendations, and general support.</p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-comments fa-2x text-primary mb-2"></i>
                                    <h6>Start a Conversation</h6>
                                    <p class="small text-muted">Ask me anything about parenting, activities, or wellness</p>
                                    <a href="{{ route('ai.chat') }}" class="btn btn-primary btn-sm">Start Chat</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <i class="fas fa-lightbulb fa-2x text-success mb-2"></i>
                                    <h6>Get Recommendations</h6>
                                    <p class="small text-muted">Receive personalized activity and service suggestions</p>
                                    <button class="btn btn-success btn-sm" onclick="getRecommendations()">Get Ideas</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function getRecommendations() {
    alert('Recommendation feature coming soon!');
}
</script>
@endsection
