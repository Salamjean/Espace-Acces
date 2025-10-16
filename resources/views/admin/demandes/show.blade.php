@extends('admin.layouts.template')
@section('content')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Détails de la Demande #{{ $personne->numero_demande }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.demandes.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Informations de la personne -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informations de la Personne</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Nom complet</th>
                                    <td>
                                        {{ $personne->prenom }} {{ $personne->name }}
                                        @if($personne->est_demandeur_principal)
                                            <span class="badge badge-primary ml-2">Demandeur principal</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $personne->email }}</td>
                                </tr>
                                <tr>
                                    <th>Contact</th>
                                    <td>{{ $personne->contact }}</td>
                                </tr>
                                <tr>
                                    <th>Fonction</th>
                                    <td>{{ $personne->fonction ?? 'Non spécifié' }}</td>
                                </tr>
                                <tr>
                                    <th>Structure</th>
                                    <td>{{ $personne->structure ?? 'Non spécifiée' }}</td>
                                </tr>
                                <tr>
                                    <th>Adresse</th>
                                    <td>{{ $personne->adresse ?? 'Non spécifiée' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Détails de la Visite</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Date de début</th>
                                    <td>{{ \Carbon\Carbon::parse($personne->date_visite)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Date de fin</th>
                                    <td>{{ \Carbon\Carbon::parse($personne->date_fin_visite)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Heure de début</th>
                                    <td>{{ $personne->heure_visite }}</td>
                                </tr>
                                <tr>
                                    <th>Heure de fin</th>
                                    <td>{{ $personne->heure_fin_visite ?? 'Non spécifiée' }}</td>
                                </tr>
                                <tr>
                                    <th>Type de demande</th>
                                    <td>
                                        @if($personne->nbre_perso == 1)
                                            <span class="badge badge-success">Individuelle</span>
                                        @else
                                            <span class="badge badge-warning">Groupe ({{ $personne->nbre_perso }} personnes)</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Groupe ID</th>
                                    <td><code>{{ $personne->groupe_id }}</code></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Informations complémentaires -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Informations Complémentaires</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Motif de la visite</th>
                                    <td>{{ $personne->motif_visite }}</td>
                                </tr>
                                <tr>
                                    <th>Description détaillée</th>
                                    <td>{{ $personne->description_detaille ?? 'Non fournie' }}</td>
                                </tr>
                                <tr>
                                    <th>Type d'intervention</th>
                                    <td>{{ $personne->type_intervention ?? 'Non spécifié' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Informations véhicule -->
                    @if($personne->marque_voiture || $personne->modele_voiture || $personne->immatriculation_voiture)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Informations du Véhicule</h5>
                            <table class="table table-bordered">
                                @if($personne->marque_voiture)
                                <tr>
                                    <th>Marque</th>
                                    <td>{{ $personne->marque_voiture }}</td>
                                </tr>
                                @endif
                                @if($personne->modele_voiture)
                                <tr>
                                    <th>Modèle</th>
                                    <td>{{ $personne->modele_voiture }}</td>
                                </tr>
                                @endif
                                @if($personne->immatriculation_voiture)
                                <tr>
                                    <th>Immatriculation</th>
                                    <td>{{ $personne->immatriculation_voiture }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Autres personnes du groupe -->
                    @if($personnesGroupe && $personnesGroupe->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Autres Personnes du Groupe</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Prénom</th>
                                            <th>Email</th>
                                            <th>Contact</th>
                                            <th>Fonction</th>
                                            <th>Structure</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($personnesGroupe as $autrePersonne)
                                        <tr>
                                            <td>{{ $autrePersonne->name }}</td>
                                            <td>{{ $autrePersonne->prenom }}</td>
                                            <td>{{ $autrePersonne->email }}</td>
                                            <td>{{ $autrePersonne->contact }}</td>
                                            <td>{{ $autrePersonne->fonction }}</td>
                                            <td>{{ $autrePersonne->structure }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Informations de la demande -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Informations de la Demande</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Numéro de demande</th>
                                    <td><span class="badge badge-primary">{{ $personne->numero_demande }}</span></td>
                                </tr>
                                <tr>
                                    <th>Statut</th>
                                    <td>
                                        @php
                                            $statutClasses = [
                                                'en_attente' => 'warning',
                                                'approuve' => 'success',
                                                'rejete' => 'danger',
                                                'annule' => 'secondary',
                                                'termine' => 'info'
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $statutClasses[$personne->statut] ?? 'secondary' }}">
                                            {{ ucfirst(str_replace('_', ' ', $personne->statut)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Date de soumission</th>
                                    <td>{{ $personne->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Dernière modification</th>
                                    <td>{{ $personne->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Informations de Traitement</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Demandeur</th>
                                    <td>
                                        @if($personne->demandeur)
                                            {{ $personne->demandeur->name }} {{ $personne->demandeur->prenom }}
                                        @else
                                            Non spécifié
                                        @endif
                                    </td>
                                </tr>
                                @if($personne->motif_rejet)
                                <tr>
                                    <th>Motif de rejet</th>
                                    <td class="text-danger">{{ $personne->motif_rejet }}</td>
                                </tr>
                                @endif
                                @if($personne->code_acces)
                                <tr>
                                    <th>Code d'accès</th>
                                    <td><span class="badge badge-success">{{ $personne->code_acces }}</span></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Documents joints -->
                    @if($personne->documents_joints)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Documents Joints</h5>
                            <div class="documents-list">
                                @php
                                    $documents = json_decode($personne->documents_joints);
                                @endphp
                                @foreach($documents as $document)
                                <div class="document-item mb-2">
                                    <i class="bi bi-file-earmark"></i>
                                    <span class="ml-2">{{ basename($document) }}</span>
                                    <a href="{{ asset('storage/' . $document) }}" target="_blank" class="btn btn-sm btn-outline-primary ml-2">
                                        <i class="bi bi-download"></i> Télécharger
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.document-item {
    display: flex;
    align-items: center;
    padding: 0.5rem;
    background: #f8f9fa;
    border-radius: 5px;
}
</style>
@endsection