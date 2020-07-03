import { TestBed } from '@angular/core/testing';

import { RemoveService } from './remove.service';

describe('RemoveService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: RemoveService = TestBed.get(RemoveService);
    expect(service).toBeTruthy();
  });
});
